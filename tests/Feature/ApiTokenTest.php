<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_receive_token(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'expires_in', 'user'])
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.email', 'john@example.com');

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_api_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_api_user_requires_token(): void
    {
        $this->getJson('/api/user')->assertStatus(401);
    }

    public function test_api_user_returns_authenticated_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('email', $user->email);
    }

    public function test_api_logout_deletes_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_revoked_token_is_rejected(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $user->tokens()->delete();
        $this->assertDatabaseCount('personal_access_tokens', 0);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user')
            ->assertStatus(401);
    }

    public function test_token_expires_after_three_hours(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        try {
            Carbon::setTestNow(now()->addHours(3)->addMinute());

            $this->withHeader('Authorization', 'Bearer '.$token)
                ->getJson('/api/user')
                ->assertStatus(401);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_token_expiration_is_three_hours(): void
    {
        $this->assertSame(180, (int) config('sanctum.expiration'));
    }
}
