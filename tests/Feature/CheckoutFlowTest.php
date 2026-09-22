<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create();
    }

    private function makeCartItem(User $user, float $price = 10.00): void
    {
        $category = Category::create(['name' => 'Meat', 'slug' => 'meat']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Beef Cube',
            'slug' => 'beef-cube',
            'price' => $price,
            'stock_quantity' => 10,
            'max_stock' => 50,
        ]);

        Cart::current()->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'price_at_add' => $price,
        ]);
    }

    private function configureCod(): void
    {
        PaymentGateway::create([
            'code' => 'cod',
            'name' => 'Payment on Delivery',
            'is_enabled' => true,
            'fee_percent' => 0.00,
        ]);
    }

    private function configurePaystack(): void
    {
        PaymentGateway::create([
            'code' => 'paystack',
            'name' => 'Paystack',
            'is_enabled' => true,
            'fee_percent' => 2.00,
            'config' => ['secret_key' => 'sk_test_xxx', 'public_key' => 'pk_test_xxx', 'mode' => 'sandbox'],
        ]);
    }

    private function checkoutData(string $method = 'cod'): array
    {
        return [
            'full_name' => 'John Doe',
            'phone' => '123456789',
            'email' => 'john@example.com',
            'address_line_1' => '1 Street',
            'city' => 'Accra',
            'state' => 'Greater Accra',
            'country' => 'Ghana',
            'payment_method' => $method,
        ];
    }

    public function test_cod_checkout_creates_pending_payment_order(): void
    {
        $this->configureCod();

        $user = $this->user();
        $this->actingAs($user);
        $this->makeCartItem($user);

        $this->post(route('checkout.store'), $this->checkoutData('cod'))
            ->assertRedirect();

        $order = Order::where('user_id', $user->id)->first();

        $this->assertNotNull($order);
        $this->assertSame('confirmed', $order->status);
        $this->assertSame('pending', $order->payment_status);

        $payment = Payment::where('order_id', $order->id)->first();
        $this->assertNotNull($payment);
        $this->assertSame('pending', $payment->status);

        $this->assertSame(0, Cart::current()->items()->count());
    }

    public function test_paystack_checkout_redirects_to_gateway(): void
    {
        $this->configurePaystack();
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'data' => ['authorization_url' => 'https://checkout.paystack.com/abc123'],
            ]),
        ]);

        $user = $this->user();
        $this->actingAs($user);
        $this->makeCartItem($user);

        $this->post(route('checkout.store'), $this->checkoutData('paystack'))
            ->assertRedirect('https://checkout.paystack.com/abc123');

        $order = Order::where('user_id', $user->id)->first();
        $this->assertSame('pending', $order->status);
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame(20.40, (float) $order->total);
    }

    public function test_paystack_callback_marks_paid(): void
    {
        $this->configurePaystack();
        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'data' => ['status' => 'success'],
            ]),
        ]);

        $user = $this->user();
        $order = Order::create([
            'order_number' => 'GR-TEST',
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_method' => 'paystack',
            'payment_status' => 'pending',
            'subtotal' => 20.00,
            'total' => 20.40,
            'delivery_full_name' => 'John Doe',
            'delivery_phone' => '123',
            'delivery_address_line_1' => '1 Street',
            'delivery_city' => 'Accra',
            'delivery_state' => 'Greater Accra',
        ]);

        $payment = $order->payments()->create([
            'user_id' => $user->id,
            'method' => 'paystack',
            'amount' => 20.40,
            'status' => 'pending',
            'provider_reference' => 'UT-REF-123',
        ]);

        $this->get(route('payment.callback', ['reference' => 'UT-REF-123']))
            ->assertRedirect(route('order.confirmation', $order));

        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertSame('confirmed', $order->fresh()->status);
        $this->assertSame('paid', $order->fresh()->payment_status);
    }

    public function test_failed_payment_cancels_order(): void
    {
        $this->configurePaystack();
        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'data' => ['status' => 'failed'],
            ]),
        ]);

        $user = $this->user();
        $order = Order::create([
            'order_number' => 'GR-FAIL',
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_method' => 'paystack',
            'payment_status' => 'pending',
            'subtotal' => 20.00,
            'total' => 20.40,
            'delivery_full_name' => 'John Doe',
            'delivery_phone' => '123',
            'delivery_address_line_1' => '1 Street',
            'delivery_city' => 'Accra',
            'delivery_state' => 'Greater Accra',
        ]);

        $payment = $order->payments()->create([
            'user_id' => $user->id,
            'method' => 'paystack',
            'amount' => 20.40,
            'status' => 'pending',
            'provider_reference' => 'UT-FAIL-123',
        ]);

        $this->get(route('payment.callback', ['reference' => 'UT-FAIL-123']));

        $this->assertSame('failed', $payment->fresh()->status);
        $this->assertSame('cancelled', $order->fresh()->status);
    }

    public function test_webhook_is_idempotent(): void
    {
        $this->configurePaystack();

        $user = $this->user();
        $order = Order::create([
            'order_number' => 'GR-WEB',
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_method' => 'paystack',
            'payment_status' => 'pending',
            'subtotal' => 20.00,
            'total' => 20.40,
            'delivery_full_name' => 'John Doe',
            'delivery_phone' => '123',
            'delivery_address_line_1' => '1 Street',
            'delivery_city' => 'Accra',
            'delivery_state' => 'Greater Accra',
        ]);

        $payment = $order->payments()->create([
            'user_id' => $user->id,
            'method' => 'paystack',
            'amount' => 20.40,
            'status' => 'pending',
            'provider_reference' => 'UT-WEB-123',
        ]);

        $payload = json_encode(['event' => 'charge.success', 'data' => ['reference' => 'UT-WEB-123']]);
        $signature = hash_hmac('sha512', $payload, 'sk_test_xxx');

        $this->postJson(route('payment.webhook'), json_decode($payload, true), ['x-paystack-signature' => $signature])
            ->assertOk();

        $this->postJson(route('payment.webhook'), json_decode($payload, true), ['x-paystack-signature' => $signature])
            ->assertOk();

        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertSame(1, Payment::where('provider_reference', 'UT-WEB-123')->count());
    }

    public function test_user_purchases_shows_order(): void
    {
        $user = $this->user();
        $order = Order::create([
            'order_number' => 'GR-LIST',
            'user_id' => $user->id,
            'status' => 'confirmed',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'subtotal' => 20.00,
            'total' => 20.00,
            'delivery_full_name' => 'John Doe',
            'delivery_phone' => '123',
            'delivery_address_line_1' => '1 Street',
            'delivery_city' => 'Accra',
            'delivery_state' => 'Greater Accra',
        ]);

        $this->actingAs($user)
            ->get(route('user.purchases'))
            ->assertOk()
            ->assertSee('GR-LIST');
    }

    public function test_checkout_prefills_saved_address(): void
    {
        $this->configureCod();

        $user = $this->user();
        $this->actingAs($user);
        $this->makeCartItem($user);

        Address::create([
            'user_id' => $user->id,
            'label' => 'Home',
            'full_name' => 'John Doe',
            'phone' => '123456789',
            'address_line_1' => '123 Main Street',
            'city' => 'Accra',
            'state' => 'Greater Accra',
            'country' => 'Ghana',
            'is_default' => true,
        ]);

        $this->get(route('checkout'))
            ->assertOk()
            ->assertSee('123 Main Street')
            ->assertSee('Accra');
    }

    public function test_paystack_uses_registered_email(): void
    {
        $this->configurePaystack();

        $capturedEmail = null;
        Http::fake([
            'api.paystack.co/transaction/initialize' => function ($request) use (&$capturedEmail) {
                $capturedEmail = $request['email'];

                return Http::response(['status' => true, 'data' => ['authorization_url' => 'https://checkout.paystack.com/xyz']]);
            },
        ]);

        $user = User::factory()->create(['email' => 'registered@example.com']);
        $this->actingAs($user);
        $this->makeCartItem($user);

        $this->post(route('checkout.store'), $this->checkoutData('paystack'));

        $this->assertSame('registered@example.com', $capturedEmail);
    }
}
