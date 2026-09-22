<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('admin.products', compact('products', 'categories'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);

        $data['slug'] = $this->uniqueSlug($request->name);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        $product = Product::create($data);

        $this->storeImage($request, $product);

        return back()->with('status', 'Product added successfully.');
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);

        $data['slug'] = $this->uniqueSlug($request->name, $product->id);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        $product->update($data);

        $this->storeImage($request, $product);

        return back()->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('status', 'Product deleted successfully.');
    }

    private function storeImage(ProductRequest $request, Product $product): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->update(['image' => $request->file('image')->store('products', 'public')]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 2;

        while (Product::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
