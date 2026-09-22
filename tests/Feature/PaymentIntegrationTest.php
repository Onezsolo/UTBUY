<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        return $admin;
    }

    private function makePayment(User $user, string $status = 'pending'): Payment
    {
        $order = Order::create([
            'order_number' => 'GR-'.$user->id.'-'.random_int(1000, 9999),
            'user_id' => $user->id,
            'subtotal' => 100.00,
            'total' => 100.00,
            'delivery_full_name' => $user->name,
            'delivery_phone' => '123456789',
            'delivery_address_line_1' => '1 Street',
            'delivery_city' => 'Accra',
            'delivery_state' => 'Greater Accra',
        ]);

        return Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'method' => 'paystack',
            'amount' => 100.00,
            'status' => $status,
        ]);
    }

    public function test_admin_approve_payment_updates_order_and_payment(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePayment($user);

        $this->actingAs($this->admin())
            ->put(route('admin.transactions.approve', $payment))
            ->assertRedirect();

        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertSame('paid', $payment->order->fresh()->payment_status);
    }

    public function test_admin_refund_payment_updates_order_and_payment(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePayment($user, 'paid');

        $this->actingAs($this->admin())
            ->put(route('admin.transactions.refund', $payment))
            ->assertRedirect();

        $this->assertSame('refunded', $payment->fresh()->status);
        $this->assertSame('refunded', $payment->order->fresh()->payment_status);
    }

    public function test_user_transactions_page_reflects_admin_changes(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePayment($user);

        $this->actingAs($user)
            ->get(route('user.transactions'))
            ->assertOk()
            ->assertSee('TXN-'.$payment->id);

        $this->actingAs($this->admin())
            ->put(route('admin.transactions.approve', $payment));

        $this->actingAs($user)
            ->get(route('user.transactions'))
            ->assertOk()
            ->assertSee('Paid');
    }
}
