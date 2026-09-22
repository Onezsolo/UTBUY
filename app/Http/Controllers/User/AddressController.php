<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = Address::where('user_id', auth()->id())
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('user.addresses', compact('addresses'));
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['is_default']);
        $data['user_id'] = auth()->id();

        $address = Address::create($data);

        if ($request->boolean('is_default') || Address::where('user_id', auth()->id())->count() === 1) {
            $this->makeDefault($address);
        }

        return back()->with('status', 'Address added successfully.');
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $data = $request->validated();
        unset($data['is_default']);
        $data['is_default'] = $request->boolean('is_default');

        $address->update($data);

        if ($request->boolean('is_default')) {
            $this->makeDefault($address);
        } else {
            $this->ensureDefaultExists();
        }

        return back()->with('status', 'Address updated successfully.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $this->ensureDefaultExists();
        }

        return back()->with('status', 'Address deleted successfully.');
    }

    public function setDefault(Address $address): RedirectResponse
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $this->makeDefault($address);

        return back()->with('status', 'Default address updated.');
    }

    private function makeDefault(Address $address): void
    {
        DB::transaction(function () use ($address) {
            Address::where('user_id', auth()->id())
                ->whereKeyNot($address->id)
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);
        });
    }

    private function ensureDefaultExists(): void
    {
        if (Address::where('user_id', auth()->id())->where('is_default', true)->doesntExist()) {
            Address::where('user_id', auth()->id())
                ->orderBy('id')
                ->first()
                ?->update(['is_default' => true]);
        }
    }
}
