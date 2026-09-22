@extends('layouts.admin', ['active' => 'products'])

@section('title', 'Admin Products - Grocery')

@section('page')
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">Products</h1>
        <button type="button" onclick="openProductModal()" class="bg-admin text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition flex items-center gap-2"><i class="fas fa-plus"></i> Add Product</button>
    </header>

    <div class="p-6">
        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4 flex items-center gap-2"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        <div class="bg-white rounded-xl card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500 bg-gray-50">
                            <th class="p-4 font-medium">Product</th>
                            <th class="p-4 font-medium">Category</th>
                            <th class="p-4 font-medium">Price</th>
                            <th class="p-4 font-medium">Stock</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($products as $product)
                            @php
                                if ($product->stock_quantity <= 0) {
                                    $stockState = 'out';
                                } elseif ($product->max_stock > 0 && ($product->stock_quantity / $product->max_stock) < 0.2) {
                                    $stockState = 'low';
                                } else {
                                    $stockState = 'in';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/'.$product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-box text-gray-400"></i></div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-800">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $product->unit ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-gray-600">{{ $product->category->name ?? '—' }}</td>
                                <td class="p-4">
                                    <div class="font-bold">₵{{ number_format((float) $product->price, 2) }}</div>
                                    @if ($product->sale_price)
                                        <div class="text-xs text-gray-400 line-through">₵{{ number_format((float) $product->sale_price, 2) }}</div>
                                    @endif
                                </td>
                                <td class="p-4"><span class="font-medium">{{ $product->stock_quantity }}</span></td>
                                <td class="p-4">
                                    @if ($stockState === 'out')
                                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">Out of Stock</span>
                                    @elseif ($stockState === 'low')
                                        <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">Low Stock</span>
                                    @else
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">In Stock</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <button type="button" class="text-primary text-sm hover:underline mr-3"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-category="{{ $product->category_id }}"
                                        data-unit="{{ $product->unit }}"
                                        data-sku="{{ $product->sku }}"
                                        data-price="{{ $product->price }}"
                                        data-sale="{{ $product->sale_price }}"
                                        data-stock="{{ $product->stock_quantity }}"
                                        data-max="{{ $product->max_stock }}"
                                        data-short="{{ $product->short_description }}"
                                        data-description="{{ $product->description }}"
                                        data-active="{{ $product->is_active ? '1' : '0' }}"
                                        data-featured="{{ $product->is_featured ? '1' : '0' }}"
                                        onclick="editProduct(this)"><i class="fas fa-edit"></i></button>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Delete this product?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm hover:underline"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-10 text-center text-gray-400">No products yet. Click "Add Product" to create one.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200">{{ $products->links() }}</div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="fixed inset-0 bg-black/50 z-50 {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800" id="productModalTitle">Add New Product</h3>
                <button type="button" onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.products.store') }}" id="productForm" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="productMethod" value="POST">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
                        <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" id="productName" value="{{ old('name') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('name') border-red-500 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="productCategory" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('category_id') border-red-500 @enderror">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <input type="text" name="unit" id="productUnit" value="{{ old('unit') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin" placeholder="e.g. 500g pack">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (₵)</label>
                        <input type="number" step="0.01" min="0" name="price" id="productPrice" value="{{ old('price') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('price') border-red-500 @enderror">
                        @error('price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price (₵)</label>
                        <input type="number" step="0.01" min="0" name="sale_price" id="productSalePrice" value="{{ old('sale_price') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" min="0" name="stock_quantity" id="productStock" value="{{ old('stock_quantity', 0) }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('stock_quantity') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Stock</label>
                        <input type="number" min="0" name="max_stock" id="productMaxStock" value="{{ old('max_stock', 0) }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('max_stock') border-red-500 @enderror">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                    <input type="text" name="short_description" id="productShort" value="{{ old('short_description') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="productDescription" rows="3" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                    <input type="file" name="image" id="productImage" accept="image/*" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none @error('image') border-red-500 @enderror">
                    @error('image')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" id="productActive" value="1" checked class="rounded border-gray-300 text-admin focus:ring-admin"> Active</label>
                    <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_featured" id="productFeatured" value="1" class="rounded border-gray-300 text-admin focus:ring-admin"> Featured</label>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeProductModal()" class="border border-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-admin text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition">Save Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openProductModal() {
        var form = document.getElementById('productForm');
        form.reset();
        form.action = "{{ route('admin.products.store') }}";
        document.getElementById('productMethod').value = 'POST';
        document.getElementById('productModalTitle').textContent = 'Add New Product';
        document.getElementById('productActive').checked = true;
        document.getElementById('productFeatured').checked = false;
        document.getElementById('productModal').classList.remove('hidden');
    }
    function editProduct(btn) {
        var d = btn.dataset;
        var form = document.getElementById('productForm');
        form.reset();
        form.action = "{{ route('admin.products.update', '__ID__') }}".replace('__ID__', d.id);
        document.getElementById('productMethod').value = 'PUT';
        document.getElementById('productModalTitle').textContent = 'Edit Product';
        document.getElementById('productName').value = d.name;
        document.getElementById('productCategory').value = d.category;
        document.getElementById('productUnit').value = d.unit;
        document.getElementById('productPrice').value = d.price;
        document.getElementById('productSalePrice').value = d.sale;
        document.getElementById('productStock').value = d.stock;
        document.getElementById('productMaxStock').value = d.max;
        document.getElementById('productShort').value = d.short;
        document.getElementById('productDescription').value = d.description;
        document.getElementById('productActive').checked = d.active === '1';
        document.getElementById('productFeatured').checked = d.featured === '1';
        document.getElementById('productModal').classList.remove('hidden');
    }
    function closeProductModal() { document.getElementById('productModal').classList.add('hidden'); }
</script>
@endpush
