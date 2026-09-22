<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    private function addressData(array $overrides = []): array
    {
        return array_merge([
            'label' => 'Home',
            'full_name' => 'John Doe',
            'phone' => '+1 234 567 890',
            'address_line_1' => '123 Main Street',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'Ghana',
        ], $overrides);
    }

    public function test_guest_is_redirected_from_addresses(): void
    {
        $this->get('/user/addresses')->assertRedirect(route('login'));
    }

    public function test_user_can_add_address(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('user.addresses.store'), $this->addressData())
            ->assertRedirect();

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'label' => 'Home',
            'is_default' => 1,
        ]);
    }

    public function test_first_address_becomes_default(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('user.addresses.store'), $this->addressData());
        $this->actingAs($user)->post(route('user.addresses.store'), $this->addressData(['label' => 'Office']));

        $this->assertSame(1, Address::where('user_id', $user->id)->where('is_default', true)->count());
    }

    public function test_user_can_view_addresses(): void
    {
        $user = User::factory()->create();
        $address = Address::create($this->addressData() + ['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('user.addresses'))
            ->assertOk()
            ->assertSee($address->full_name)
            ->assertSee($address->label);
    }

    public function test_user_can_update_address(): void
    {
        $user = User::factory()->create();
        $address = Address::create($this->addressData() + ['user_id' => $user->id]);

        $this->actingAs($user)
            ->put(route('user.addresses.update', $address), $this->addressData(['label' => 'Office']))
            ->assertRedirect();

        $this->assertDatabaseHas('addresses', ['id' => $address->id, 'label' => 'Office']);
    }

    public function test_user_can_delete_address(): void
    {
        $user = User::factory()->create();
        $address = Address::create($this->addressData() + ['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('user.addresses.destroy', $address))
            ->assertRedirect();

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_user_cannot_update_another_users_address(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $address = Address::create($this->addressData() + ['user_id' => $owner->id]);

        $this->actingAs($other)
            ->put(route('user.addresses.update', $address), $this->addressData(['label' => 'Hacked']))
            ->assertStatus(403);
    }

    public function test_set_default_updates_default_address(): void
    {
        $user = User::factory()->create();
        $a1 = Address::create($this->addressData() + ['user_id' => $user->id, 'is_default' => true]);
        $a2 = Address::create($this->addressData(['label' => 'Office']) + ['user_id' => $user->id, 'is_default' => false]);

        $this->actingAs($user)
            ->put(route('user.addresses.default', $a2))
            ->assertRedirect();

        $this->assertDatabaseHas('addresses', ['id' => $a2->id, 'is_default' => 1]);
        $this->assertDatabaseHas('addresses', ['id' => $a1->id, 'is_default' => 0]);
    }
}
