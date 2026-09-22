<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategoryAndProduct(): array
    {
        $category = Category::create(['name' => 'Meat', 'slug' => 'meat', 'is_active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Beef Cube',
            'slug' => 'beef-cube',
            'price' => 18.00,
            'stock_quantity' => 10,
            'max_stock' => 50,
            'is_active' => true,
        ]);

        return [$category, $product];
    }

    public function test_home_page_shows_categories_and_products(): void
    {
        $this->makeCategoryAndProduct();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Meat')
            ->assertSee('Beef Cube');
    }

    public function test_products_page_shows_products(): void
    {
        $this->makeCategoryAndProduct();

        $this->get(route('products'))
            ->assertOk()
            ->assertSee('Beef Cube')
            ->assertSee('Meat');
    }

    public function test_product_details_page_shows_product(): void
    {
        [, $product] = $this->makeCategoryAndProduct();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Beef Cube')
            ->assertSee('₵18.00');
    }

    public function test_inactive_product_returns_404(): void
    {
        [, $product] = $this->makeCategoryAndProduct();
        $product->update(['is_active' => false]);

        $this->get(route('products.show', $product))->assertStatus(404);
    }
}
