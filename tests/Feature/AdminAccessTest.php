<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_user_dashboard(): void
    {
        $this->get('/user/dashboard')->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200);
    }

    public function test_admin_can_login_through_admin_portal(): void
    {
        $admin = User::factory()->create(['password' => Hash::make('password123')]);
        $admin->role = 'admin';
        $admin->save();

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_customer_cannot_login_through_admin_portal(): void
    {
        $customer = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->post('/admin/login', [
            'email' => $customer->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
