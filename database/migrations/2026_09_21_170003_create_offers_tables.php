<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount', 'buy_x_get_y', 'free_shipping'])->default('percentage');
            $table->decimal('value', 10, 2)->default(0.00);
            $table->unsignedInteger('buy_quantity')->nullable();
            $table->unsignedInteger('get_quantity')->nullable();
            $table->enum('scope', ['all', 'category', 'product'])->default('all');
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'start_date', 'end_date']);
        });

        Schema::create('offer_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('category_id');
            $table->primary(['offer_id', 'category_id']);
            $table->foreign('offer_id')->references('id')->on('special_offers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('offer_products', function (Blueprint $table) {
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('product_id');
            $table->primary(['offer_id', 'product_id']);
            $table->foreign('offer_id')->references('id')->on('special_offers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_products');
        Schema::dropIfExists('offer_categories');
        Schema::dropIfExists('special_offers');
    }
};
