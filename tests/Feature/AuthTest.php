<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->post(route('signup.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1 234 567 890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'phone' => '+1 234 567 890',
            'role' => 'customer',
        ]);
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->post(route('signup.store'), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1 234 567 890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_registration_requires_valid_fields(): void
    {
        $response = $this->post(route('signup.store'), []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'phone', 'password', 'terms']);
        $this->assertGuest();
    }

    public function test_registration_rejects_password_mismatch(): void
    {
        $response = $this->post(route('signup.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1 234 567 890',
            'password' => 'password123',
            'password_confirmation' => 'different',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_requires_fields(): void
    {
        $response = $this->post(route('login.store'), []);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }
}
