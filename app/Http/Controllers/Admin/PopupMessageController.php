<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PopupMessageController extends Controller
{
    public function index(): View
    {
        $messages = PopupMessage::latest()->get();

        return view('admin.popup-messages', compact('messages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'display_mode' => ['required', 'in:every_login,scheduled'],
            'start_date' => ['nullable', 'required_if:display_mode,scheduled', 'date'],
            'end_date' => ['nullable', 'required_if:display_mode,scheduled', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        PopupMessage::create($data);

        return back()->with('status', 'Popup message added successfully.');
    }

    public function update(Request $request, PopupMessage $message): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'display_mode' => ['required', 'in:every_login,scheduled'],
            'start_date' => ['nullable', 'required_if:display_mode,scheduled', 'date'],
            'end_date' => ['nullable', 'required_if:display_mode,scheduled', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $message->update($data);

        return back()->with('status', 'Popup message updated successfully.');
    }

    public function destroy(PopupMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('status', 'Popup message deleted successfully.');
    }
}
