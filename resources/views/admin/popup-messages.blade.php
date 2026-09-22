@extends('layouts.admin', ['active' => 'popup-messages'])

@section('title', 'Admin Popup Messages - Grocery')

@section('page')
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">Popup Messages</h1>
        <button type="button" onclick="openModal()" class="bg-admin text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-admin-light transition flex items-center gap-2"><i class="fas fa-plus"></i> Add Message</button>
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
                            <th class="p-4 font-medium">Message</th>
                            <th class="p-4 font-medium">Display</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($messages as $message)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium text-gray-800">{{ $message->title }}</td>
                                <td class="p-4 text-gray-600">{{ $message->message }}</td>
                                <td class="p-4">
                                    @if ($message->display_mode === 'every_login')
                                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">Every Login</span>
                                    @else
                                        <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full">Scheduled</span>
                                        <div class="text-xs text-gray-500 mt-1">{{ $message->start_date?->format('M d, Y') }} - {{ $message->end_date?->format('M d, Y') }}</div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if ($message->is_active) <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Active</span>
                                    @else <span class="bg-gray-100 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-full">Inactive</span> @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <button type="button" class="text-primary text-sm hover:underline mr-3"
                                        data-id="{{ $message->id }}" data-title="{{ $message->title }}" data-message="{{ $message->message }}" data-mode="{{ $message->display_mode }}"
                                        data-start="{{ $message->start_date?->format('Y-m-d\TH:i') }}" data-end="{{ $message->end_date?->format('Y-m-d\TH:i') }}"
                                        data-active="{{ $message->is_active ? '1' : '0' }}"
                                        onclick="editMessage(this)"><i class="fas fa-edit"></i></button>
                                    <form method="POST" action="{{ route('admin.popup-messages.destroy', $message) }}" class="inline" onsubmit="return confirm('Delete this message?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm hover:underline"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-10 text-center text-gray-400">No popup messages yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="popupModal" class="fixed inset-0 bg-black/50 z-50 {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add Popup Message</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.popup-messages.store') }}" id="popupForm" class="p-6 space-y-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" id="message" rows="3" required class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display Mode</label>
                    <select name="display_mode" id="display_mode" onchange="toggleDateFields()" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin">
                        <option value="every_login">Every Login</option>
                        <option value="scheduled">Scheduled Period</option>
                    </select>
                </div>
                <div id="dateFields" class="hidden grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('start_date') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="input-field w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-admin @error('end_date') border-red-500 @enderror">
                    </div>
                </div>
                @error('start_date')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('end_date')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
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
    function toggleDateFields() {
        var scheduled = document.getElementById('display_mode').value === 'scheduled';
        document.getElementById('dateFields').classList.toggle('hidden', !scheduled);
    }
    function openModal() {
        var form = document.getElementById('popupForm');
        form.reset();
        form.action = "{{ route('admin.popup-messages.store') }}";
        document.getElementById('method').value = 'POST';
        document.getElementById('modalTitle').textContent = 'Add Popup Message';
        document.getElementById('is_active').checked = true;
        document.getElementById('display_mode').value = 'every_login';
        toggleDateFields();
        document.getElementById('popupModal').classList.remove('hidden');
    }
    function editMessage(btn) {
        var d = btn.dataset;
        var form = document.getElementById('popupForm');
        form.reset();
        form.action = "{{ route('admin.popup-messages.update', '__ID__') }}".replace('__ID__', d.id);
        document.getElementById('method').value = 'PUT';
        document.getElementById('modalTitle').textContent = 'Edit Popup Message';
        document.getElementById('title').value = d.title;
        document.getElementById('message').value = d.message;
        document.getElementById('display_mode').value = d.mode;
        document.getElementById('start_date').value = d.start;
        document.getElementById('end_date').value = d.end;
        document.getElementById('is_active').checked = d.active === '1';
        toggleDateFields();
        document.getElementById('popupModal').classList.remove('hidden');
    }
    function closeModal() { document.getElementById('popupModal').classList.add('hidden'); }
</script>
@endpush
