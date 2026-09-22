<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = Cart::current()->items()->with('product')->get();

        return view('shop.cart', compact('items'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $cart = Cart::current();
        $quantity = max(1, $request->integer('quantity', 1));
        $price = (float) $product->sale_price > 0 ? $product->sale_price : $product->price;

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price_at_add' => $price,
            ]);
        }

        return back()->with('status', 'Added to cart.');
    }

    public function update(Request $request, CartItem $item): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item->update(['quantity' => $request->integer('quantity')]);

        return back();
    }

    public function remove(CartItem $item): RedirectResponse
    {
        $item->delete();

        return back()->with('status', 'Item removed from cart.');
    }
}
