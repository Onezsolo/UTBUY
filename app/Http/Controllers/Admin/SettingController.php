<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const STORE_KEYS = [
        'store.name' => 'store_name',
        'store.tagline' => 'store_tagline',
        'store.email' => 'store_email',
        'store.phone' => 'store_phone',
        'store.address' => 'store_address',
        'store.currency' => 'store_currency',
    ];

    public function index(): View
    {
        $settings = Setting::whereIn('key', array_keys(self::STORE_KEYS))
            ->pluck('value', 'key');

        return view('admin.system-settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_tagline' => ['nullable', 'string', 'max:255'],
            'store_email' => ['nullable', 'email', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:50'],
            'store_address' => ['nullable', 'string', 'max:255'],
            'store_currency' => ['required', 'string', 'max:10'],
        ]);

        foreach (self::STORE_KEYS as $key => $field) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $data[$field] ?? '', 'group' => 'store']
            );
        }

        return back()->with('status', 'Settings saved successfully.');
    }
}
