@extends('layouts.admin', ['active' => 'support'])

@section('title', 'Admin Support - Grocery')

@section('page')
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">Support Links</h1>
        <button type="button" onclick="openModal()" class="bg-admin text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition flex items-center gap-2"><i class="fas fa-plus"></i> Add Link</button>
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
                            <th class="p-4 font-medium">Title</th>
                            <th class="p-4 font-medium">Type</th>
                            <th class="p-4 font-medium">Link</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($links as $link)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium text-gray-800"><i class="{{ $link->icon ?: 'fas fa-link' }} mr-2 text-gray-400"></i>{{ $link->title }}</td>
                                <td class="p-4">
                                    @if ($link->type === 'whatsapp_group') <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">WhatsApp Group</span>
                                    @elseif ($link->type === 'social') <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">Social Media</span>
                                    @else <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-full">Other</span> @endif
                                </td>
                                <td class="p-4 text-gray-600 truncate max-w-[240px]">{{ $link->url ?? '—' }}</td>
                                <td class="p-4">
                                    @if ($link->is_active) <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Active</span>
                                    @else <span class="bg-gray-100 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-full">Inactive</span> @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <button type="button" class="text-primary text-sm hover:underline mr-3"
                                        data-id="{{ $link->id }}" data-title="{{ $link->title }}" data-type="{{ $link->type }}" data-url="{{ $link->url }}" data-icon="{{ $link->icon }}" data-sort="{{ $link->sort_order }}" data-active="{{ $link->is_active ? '1' : '0' }}"
                                        onclick="editLink(this)"><i class="fas fa-edit"></i></button>
                                    <form method="POST" action="{{ route('admin.support.destroy', $link) }}" class="inline" onsubmit="return confirm('Delete this link?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm hover:underline"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-10 text-center text-gray-400">No support links yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="supportModal" class="fixed inset-0 bg-black/50 z-50 {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add Support Link</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.support.store') }}" id="supportForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="method" value="POST">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('title') border-red-500 @enderror">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" id="type" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">
                        <option value="whatsapp_group">WhatsApp Group</option>
                        <option value="social">Social Media</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link / URL</label>
                    <input type="text" name="url" id="url" value="{{ old('url') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin" placeholder="https://chat.whatsapp.com/...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome class)</label>
                    <input type="text" name="icon" id="icon" value="{{ old('icon') }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin" placeholder="fab fa-whatsapp">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-gray-300 text-admin focus:ring-admin"> Active</label>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" class="border border-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-admin text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openModal() {
        var form = document.getElementById('supportForm');
        form.reset();
        form.action = "{{ route('admin.support.store') }}";
        document.getElementById('method').value = 'POST';
        document.getElementById('modalTitle').textContent = 'Add Support Link';
        document.getElementById('is_active').checked = true;
        document.getElementById('supportModal').classList.remove('hidden');
    }
    function editLink(btn) {
        var d = btn.dataset;
        var form = document.getElementById('supportForm');
        form.reset();
        form.action = "{{ route('admin.support.update', '__ID__') }}".replace('__ID__', d.id);
        document.getElementById('method').value = 'PUT';
        document.getElementById('modalTitle').textContent = 'Edit Support Link';
        document.getElementById('title').value = d.title;
        document.getElementById('type').value = d.type;
        document.getElementById('url').value = d.url;
        document.getElementById('icon').value = d.icon;
        document.getElementById('sort_order').value = d.sort;
        document.getElementById('is_active').checked = d.active === '1';
        document.getElementById('supportModal').classList.remove('hidden');
    }
    function closeModal() { document.getElementById('supportModal').classList.add('hidden'); }
</script>
@endpush
