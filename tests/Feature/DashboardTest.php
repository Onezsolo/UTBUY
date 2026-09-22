<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        return $admin;
    }

    private function makeOrder(User $user, string $number): Order
    {
        return Order::create([
            'order_number' => $number,
            'user_id' => $user->id,
            'subtotal' => 18.00,
            'total' => 18.00,
            'delivery_full_name' => $user->name,
            'delivery_phone' => '123456789',
            'delivery_address_line_1' => '1 Main Street',
            'delivery_city' => 'City',
            'delivery_state' => 'State',
        ]);
    }

    public function test_admin_dashboard_shows_database_data(): void
    {
        $admin = $this->makeAdmin();

        $category = Category::create(['name' => 'Meat', 'slug' => 'meat']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Beef Cube',
            'slug' => 'beef-cube',
            'price' => 18.00,
            'stock_quantity' => 10,
            'max_stock' => 50,
        ]);

        $customer = User::factory()->create();
        $this->makeOrder($customer, 'GR-10001');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee('GR-10001')
            ->assertSee('Beef Cube');
    }

    public function test_user_dashboard_shows_users_orders(): void
    {
        $user = User::factory()->create();
        $this->makeOrder($user, 'GR-20001');

        $response = $this->actingAs($user)->get(route('user.dashboard'));

        $response->assertOk()->assertSee('GR-20001');
    }

    public function test_user_dashboard_is_isolated_to_owner(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->makeOrder($other, 'GR-30001');

        $response = $this->actingAs($user)->get(route('user.dashboard'));

        $response->assertOk()->assertDontSee('GR-30001');
    }
}
