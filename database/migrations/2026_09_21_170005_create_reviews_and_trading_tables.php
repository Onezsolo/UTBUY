<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            $table->unique(['product_id', 'user_id']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('trade_listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'poor'])->default('good');
            $table->decimal('estimated_value', 10, 2)->nullable();
            $table->string('preferred_trade')->nullable();
            $table->enum('status', ['active', 'pending_trade', 'completed', 'cancelled'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });

        Schema::create('trade_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trade_listing_id');
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->foreign('trade_listing_id')->references('id')->on('trade_listings')->onDelete('cascade');
        });

        Schema::create('trade_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trade_listing_id');
            $table->unsignedBigInteger('from_user_id');
            $table->text('message')->nullable();
            $table->text('offered_items')->nullable();
            $table->decimal('offered_value', 10, 2)->nullable();
            $table->decimal('cash_difference', 10, 2)->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->timestamps();
            $table->index('status');
            $table->foreign('trade_listing_id')->references('id')->on('trade_listings')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_offers');
        Schema::dropIfExists('trade_images');
        Schema::dropIfExists('trade_listings');
        Schema::dropIfExists('product_reviews');
    }
};
