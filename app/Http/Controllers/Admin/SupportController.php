<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(): View
    {
        $links = SupportLink::orderBy('type')->orderBy('sort_order')->get();

        return view('admin.support', compact('links'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:whatsapp_group,social,other'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        SupportLink::create($data);

        return back()->with('status', 'Support link added successfully.');
    }

    public function update(Request $request, SupportLink $link): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:whatsapp_group,social,other'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $link->update($data);

        return back()->with('status', 'Support link updated successfully.');
    }

    public function destroy(SupportLink $link): RedirectResponse
    {
        $link->delete();

        return back()->with('status', 'Support link deleted successfully.');
    }
}
