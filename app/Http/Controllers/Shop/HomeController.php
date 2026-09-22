<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->where('is_active', true)
            ->take(10)
            ->get();

        $products = Product::where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        $saleProducts = Product::where('is_active', true)
            ->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->latest()
            ->take(4)
            ->get();

        return view('shop.home', compact('categories', 'products', 'saleProducts'));
    }
}
