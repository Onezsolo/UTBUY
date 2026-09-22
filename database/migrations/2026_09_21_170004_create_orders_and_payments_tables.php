<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->boolean('is_enabled')->default(true);
            $table->decimal('fee_percent', 5, 2)->default(0.00);
            $table->json('config')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 40)->unique();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cod', 'paystack'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('shipping_fee', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('payment_fee', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->char('currency', 3)->default('GHS');
            $table->text('customer_notes')->nullable();
            $table->string('delivery_full_name');
            $table->string('delivery_phone', 30);
            $table->string('delivery_email')->nullable();
            $table->string('delivery_address_line_1');
            $table->string('delivery_address_line_2')->nullable();
            $table->string('delivery_city', 100);
            $table->string('delivery_state', 100);
            $table->string('delivery_postal_code', 20)->nullable();
            $table->string('delivery_country', 100)->default('Ghana');
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('payment_status');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name');
            $table->string('sku', 100)->nullable();
            $table->string('unit', 50)->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });

        Schema::create('order_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('shipping_method_id')->nullable();
            $table->string('carrier', 100)->nullable();
            $table->string('tracking_number', 100)->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('set null');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('method', ['cod', 'paystack']);
            $table->decimal('amount', 10, 2);
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->char('currency', 3)->default('GHS');
            $table->enum('status', ['pending', 'authorized', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('provider_reference')->nullable();
            $table->json('provider_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_shipments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('payment_gateways');
    }
};
