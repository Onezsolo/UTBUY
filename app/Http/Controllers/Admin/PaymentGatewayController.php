<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentGatewayController extends Controller
{
    public function index(): View
    {
        $gateways = PaymentGateway::orderBy('sort_order')->get();

        return view('admin.payment-gateway', compact('gateways'));
    }

    public function update(Request $request, PaymentGateway $gateway): RedirectResponse
    {
        $data = $request->validate([
            'is_enabled' => ['nullable', 'boolean'],
            'fee_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'public_key' => ['nullable', 'string', 'max:255'],
            'secret_key' => ['nullable', 'string', 'max:255'],
            'mode' => ['nullable', 'in:live,sandbox'],
        ]);

        $gateway->is_enabled = $request->boolean('is_enabled');
        $gateway->fee_percent = $data['fee_percent'] ?? $gateway->fee_percent;

        if ($gateway->code === 'paystack') {
            $gateway->config = array_merge($gateway->config ?? [], array_filter([
                'public_key' => $data['public_key'] ?? null,
                'secret_key' => $data['secret_key'] ?? null,
                'mode' => $data['mode'] ?? null,
            ], fn ($value) => $value !== null));
        }

        $gateway->save();

        return back()->with('status', 'Payment gateway updated.');
    }
}
