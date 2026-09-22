<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('admin.categories', compact('categories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);

        $data['slug'] = $this->uniqueSlug($request->name);
        $data['is_active'] = $request->boolean('is_active');

        $category = Category::create($data);

        $this->storeImage($request, $category);

        return back()->with('status', 'Category added successfully.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);

        $data['slug'] = $this->uniqueSlug($request->name, $category->id);
        $data['is_active'] = $request->boolean('is_active');

        $category->update($data);

        $this->storeImage($request, $category);

        return back()->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('status', 'Category deleted successfully.');
    }

    private function storeImage(CategoryRequest $request, Category $category): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->update(['image' => $request->file('image')->store('categories', 'public')]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 2;

        while (Category::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
