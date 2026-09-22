<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_gateways')->insert([
            ['code' => 'cod', 'name' => 'Payment on Delivery', 'is_enabled' => true, 'fee_percent' => 0.00, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'paystack', 'name' => 'Paystack', 'is_enabled' => true, 'fee_percent' => 2.00, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('shipping_methods')->insert([
            ['name' => 'Standard Delivery', 'price' => 0.00, 'estimated_days_min' => 3, 'estimated_days_max' => 5, 'free_threshold' => 50.00, 'is_active' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Express Delivery', 'price' => 9.99, 'estimated_days_min' => 1, 'estimated_days_max' => 2, 'free_threshold' => null, 'is_active' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Store Pickup', 'price' => 0.00, 'estimated_days_min' => null, 'estimated_days_max' => null, 'free_threshold' => null, 'is_active' => true, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('order_statuses')->insert([
            ['code' => 'pending', 'name' => 'Order Placed', 'step' => 1, 'icon' => 'fa-box-open', 'color' => 'gray', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'confirmed', 'name' => 'Order Confirmed', 'step' => 2, 'icon' => 'fa-check-circle', 'color' => 'blue', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'processing', 'name' => 'Processing', 'step' => 3, 'icon' => 'fa-cogs', 'color' => 'indigo', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'shipped', 'name' => 'Shipped', 'step' => 4, 'icon' => 'fa-truck', 'color' => 'orange', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'out_for_delivery', 'name' => 'Out for Delivery', 'step' => 5, 'icon' => 'fa-shipping-fast', 'color' => 'amber', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'delivered', 'name' => 'Delivered', 'step' => 6, 'icon' => 'fa-home', 'color' => 'green', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'cancelled', 'name' => 'Cancelled', 'step' => 0, 'icon' => 'fa-times-circle', 'color' => 'red', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('settings')->insert([
            ['key' => 'products.default_image', 'value' => '/images/products/default-icon.png', 'group' => 'products', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'paystack.mode', 'value' => 'sandbox', 'group' => 'paystack', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'paystack.fee_percent', 'value' => '2.00', 'group' => 'paystack', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'store.currency', 'value' => 'GHS', 'group' => 'store', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'store.name', 'value' => 'UTBUY', 'group' => 'store', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ADMIN_PASSWORD');

        if (! $adminPassword) {
            $adminPassword = Str::password(16);
            $this->command?->warn('Generated admin password: '.$adminPassword);
            $this->command?->warn('Set ADMIN_PASSWORD in your .env file and re-run the seeder, or change it immediately.');
        }

        $admin = User::create([
            'name' => 'Administrator',
            'email' => $adminEmail,
            'password' => $adminPassword,
        ]);
        $admin->role = 'admin';
        $admin->save();
    }
}
