<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        return $admin;
    }

    public function test_admin_can_view_admin_pages(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.products'))
            ->assertOk();

        $this->actingAs($this->admin())
            ->get(route('admin.categories'))
            ->assertOk();

        $this->actingAs($this->admin())
            ->get(route('admin.users'))
            ->assertOk();

        $this->actingAs($this->admin())
            ->get(route('admin.transactions'))
            ->assertOk();

        $this->actingAs($this->admin())
            ->get(route('admin.payment-gateway'))
            ->assertOk();

        $this->actingAs($this->admin())
            ->get(route('admin.system-settings'))
            ->assertOk();
    }

    public function test_admin_can_create_category(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), ['name' => 'Meat'])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['name' => 'Meat', 'slug' => 'meat']);
    }

    public function test_admin_can_create_and_delete_product(): void
    {
        $category = Category::create(['name' => 'Meat', 'slug' => 'meat']);

        $this->actingAs($this->admin())
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'Beef Cube',
                'price' => 18.00,
                'stock_quantity' => 10,
                'max_stock' => 50,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('products', ['name' => 'Beef Cube', 'slug' => 'beef-cube']);

        $product = Product::where('name', 'Beef Cube')->first();

        $this->actingAs($this->admin())
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_add_user(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.users.store'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'customer',
                'password' => 'password123',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_admin_can_suspend_and_activate_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin())
            ->put(route('admin.users.status', $user))
            ->assertRedirect();

        $this->assertSame(false, $user->fresh()->is_active);

        $this->actingAs($this->admin())
            ->put(route('admin.users.status', $user))
            ->assertRedirect();

        $this->assertSame(true, $user->fresh()->is_active);
    }

    public function test_admin_can_update_order_status(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_number' => 'GR-10001',
            'user_id' => $user->id,
            'subtotal' => 18.00,
            'total' => 18.00,
            'delivery_full_name' => $user->name,
            'delivery_phone' => '123456789',
            'delivery_address_line_1' => '1 Street',
            'delivery_city' => 'City',
            'delivery_state' => 'State',
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.purchases.status', $order), ['status' => 'shipped'])
            ->assertRedirect();

        $this->assertSame('shipped', $order->fresh()->status);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'status_code' => 'shipped',
        ]);
    }

    public function test_admin_can_update_payment_gateway(): void
    {
        $gateway = PaymentGateway::create([
            'code' => 'paystack',
            'name' => 'Paystack',
            'fee_percent' => 2.00,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.payment-gateway.update', $gateway), [
                'is_enabled' => '1',
                'fee_percent' => '2.50',
                'public_key' => 'pk_test',
                'secret_key' => 'sk_test',
                'mode' => 'sandbox',
            ])
            ->assertRedirect();

        $gateway->refresh();

        $this->assertTrue($gateway->is_enabled);
        $this->assertSame('pk_test', $gateway->config['public_key']);
    }

    public function test_admin_can_update_system_settings(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.system-settings.update'), [
                'store_name' => 'My Store',
                'store_currency' => 'GHS',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('settings', ['key' => 'store.name', 'value' => 'My Store']);
        $this->assertSame('My Store', Setting::where('key', 'store.name')->value('value'));
    }

    public function test_product_creation_requires_category_and_price(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.products.store'), ['name' => 'Incomplete'])
            ->assertSessionHasErrors(['category_id', 'price', 'stock_quantity', 'max_stock']);

        $this->assertDatabaseCount('products', 0);
    }
}
