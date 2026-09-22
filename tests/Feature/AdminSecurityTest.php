<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(string $password = 'password123'): User
    {
        $admin = User::factory()->create(['password' => Hash::make($password)]);
        $admin->role = 'admin';
        $admin->save();

        return $admin;
    }

    public function test_successful_admin_login_is_logged(): void
    {
        $admin = $this->makeAdmin();

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('admin_login_logs', [
            'email' => $admin->email,
            'status' => 'success',
        ]);
    }

    public function test_failed_admin_login_is_logged(): void
    {
        $admin = $this->makeAdmin();

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $this->assertDatabaseHas('admin_login_logs', [
            'email' => $admin->email,
            'status' => 'failed',
        ]);
    }

    public function test_admin_account_locks_after_repeated_failures(): void
    {
        $admin = $this->makeAdmin();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => $admin->email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('admin_login_logs', 5);
    }
}
