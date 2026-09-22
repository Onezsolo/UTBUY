@extends('layouts.admin', ['active' => 'categories'])

@section('title', 'Admin Categories - Grocery')

@section('page')
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">Categories</h1>
        <button type="button" onclick="openCategoryModal()" class="bg-admin text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition flex items-center gap-2"><i class="fas fa-plus"></i> Add Category</button>
    </header>

    <div class="p-6">
        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4 flex items-center gap-2"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($categories as $category)
                <div class="bg-white rounded-xl card-shadow p-6 text-center hover:shadow-lg transition group">
                    @if ($category->image)
                        <img src="{{ asset('storage/'.$category->image) }}" class="w-16 h-16 rounded-full object-cover mx-auto mb-3">
                    @else
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fas fa-folder text-2xl text-gray-400"></i></div>
                    @endif
                    <h3 class="font-bold text-gray-800 text-lg">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $category->products_count }} products</p>
                    @unless ($category->is_active)
                        <span class="inline-block mt-1 text-xs text-red-500 font-semibold">Inactive</span>
                    @endunless
                    <div class="flex justify-center gap-3 mt-4">
                        <button type="button" class="text-primary text-sm font-medium hover:underline"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-description="{{ $category->description }}"
                            data-active="{{ $category->is_active ? '1' : '0' }}"
                            onclick="editCategory(this)"><i class="fas fa-edit"></i> Edit</button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-sm font-medium hover:underline"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-10 text-center text-gray-400">No categories yet. Click "Add Category" to create one.</div>
            @endforelse
        </div>
    </div>

    <!-- Add/Edit Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black/50 z-50 {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800" id="categoryModalTitle">Add New Category</h3>
                <button type="button" onclick="closeCategoryModal()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}" id="categoryForm" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="categoryMethod" value="POST">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
                        <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                    <input type="text" name="name" id="categoryName" value="{{ old('name') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('name') border-red-500 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="categoryDescription" rows="3" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image (optional)</label>
                    <input type="file" name="image" id="categoryImage" accept="image/*" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none @error('image') border-red-500 @enderror">
                    @error('image')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_active" id="categoryActive" value="1" checked class="rounded border-gray-300 text-admin focus:ring-admin"> Active
                </label>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeCategoryModal()" class="border border-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-admin text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition">Save Category</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openCategoryModal() {
        var form = document.getElementById('categoryForm');
        form.reset();
        form.action = "{{ route('admin.categories.store') }}";
        document.getElementById('categoryMethod').value = 'POST';
        document.getElementById('categoryModalTitle').textContent = 'Add New Category';
        document.getElementById('categoryActive').checked = true;
        document.getElementById('categoryModal').classList.remove('hidden');
    }
    function editCategory(btn) {
        var form = document.getElementById('categoryForm');
        form.reset();
        form.action = "{{ route('admin.categories.update', '__ID__') }}".replace('__ID__', btn.dataset.id);
        document.getElementById('categoryMethod').value = 'PUT';
        document.getElementById('categoryModalTitle').textContent = 'Edit Category';
        document.getElementById('categoryName').value = btn.dataset.name;
        document.getElementById('categoryDescription').value = btn.dataset.description;
        document.getElementById('categoryActive').checked = btn.dataset.active === '1';
        document.getElementById('categoryModal').classList.remove('hidden');
    }
    function closeCategoryModal() { document.getElementById('categoryModal').classList.add('hidden'); }
</script>
@endpush
