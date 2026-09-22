<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\PopupMessage;
use App\Models\SupportLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SupportFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        return $admin;
    }

    public function test_public_support_page_loads(): void
    {
        SupportLink::create([
            'title' => 'Our WhatsApp Group',
            'type' => 'whatsapp_group',
            'url' => 'https://chat.whatsapp.com/abc',
            'is_active' => true,
        ]);

        $this->get(route('support'))
            ->assertOk()
            ->assertSee('Our WhatsApp Group');
    }

    public function test_admin_can_manage_support_links(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.support.store'), [
                'title' => 'Facebook Page',
                'type' => 'social',
                'url' => 'https://facebook.com/grocery',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('support_links', ['title' => 'Facebook Page', 'type' => 'social']);
    }

    public function test_admin_can_manage_announcements(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.announcements.store'), [
                'title' => 'Free Delivery',
                'message' => 'Free delivery on orders over GHS 500',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('announcements', ['title' => 'Free Delivery']);
    }

    public function test_admin_can_manage_popup_messages(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.popup-messages.store'), [
                'title' => 'Welcome Offer',
                'message' => 'Get 10% off your first order',
                'display_mode' => 'every_login',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('popup_messages', ['title' => 'Welcome Offer']);
    }

    public function test_announcement_active_scope(): void
    {
        Announcement::create(['title' => 'Active', 'message' => 'm', 'is_active' => true]);
        Announcement::create(['title' => 'Inactive', 'message' => 'm', 'is_active' => false]);

        $this->assertSame(1, Announcement::active()->count());
    }

    public function test_popup_message_active_scope(): void
    {
        PopupMessage::create(['title' => 'Every login', 'message' => 'm', 'is_active' => true, 'display_mode' => 'every_login']);
        PopupMessage::create(['title' => 'Scheduled active', 'message' => 'm', 'is_active' => true, 'display_mode' => 'scheduled', 'start_date' => now()->subDay(), 'end_date' => now()->addDay()]);
        PopupMessage::create(['title' => 'Scheduled expired', 'message' => 'm', 'is_active' => true, 'display_mode' => 'scheduled', 'start_date' => now()->subDays(5), 'end_date' => now()->subDays(2)]);
        PopupMessage::create(['title' => 'Inactive', 'message' => 'm', 'is_active' => false, 'display_mode' => 'every_login']);

        $this->assertSame(2, PopupMessage::active()->count());
    }

    public function test_popup_appears_after_login(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        PopupMessage::create([
            'title' => 'Welcome!',
            'message' => 'Hello there',
            'is_active' => true,
            'display_mode' => 'every_login',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->get(route('home'))->assertSee('Welcome!');
    }
}
