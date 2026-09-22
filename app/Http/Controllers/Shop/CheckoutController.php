<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\PaystackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $items = Cart::current()->items()->with('product')->get();
        $gateways = PaymentGateway::where('is_enabled', true)->orderBy('sort_order')->get();
        $addresses = auth()->check()
            ? auth()->user()->addresses()->orderByDesc('is_default')->orderByDesc('id')->get()
            : collect();
        $defaultAddress = $addresses->first();

        return view('shop.checkout', compact('items', 'gateways', 'addresses', 'defaultAddress'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $cart = Cart::current();
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cod,paystack'],
        ]);

        $gateway = PaymentGateway::where('code', $data['payment_method'])
            ->where('is_enabled', true)
            ->first();

        if (! $gateway) {
            return back()->withErrors(['payment_method' => 'The selected payment method is not available.']);
        }

        $subtotal = (float) $items->sum(fn ($item) => $item->quantity * (float) $item->price_at_add);
        $fee = $data['payment_method'] === 'paystack'
            ? round($subtotal * (float) $gateway->fee_percent / 100, 2)
            : 0.0;
        $total = $subtotal + $fee;

        $order = Order::create([
            'order_number' => 'GR-'.strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'subtotal' => $subtotal,
            'discount' => 0.00,
            'shipping_fee' => 0.00,
            'tax' => 0.00,
            'payment_fee' => $fee,
            'total' => $total,
            'currency' => 'GHS',
            'delivery_full_name' => $data['full_name'],
            'delivery_phone' => $data['phone'],
            'delivery_email' => auth()->user()->email,
            'delivery_address_line_1' => $data['address_line_1'],
            'delivery_address_line_2' => $data['address_line_2'] ?? null,
            'delivery_city' => $data['city'],
            'delivery_state' => $data['state'],
            'delivery_country' => $data['country'],
            'placed_at' => now(),
        ]);

        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'sku' => $item->product->sku,
                'unit' => $item->product->unit,
                'image' => $item->product->image,
                'price' => $item->price_at_add,
                'original_price' => $item->product->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->quantity * (float) $item->price_at_add,
            ]);
        }

        $reference = 'UT-'.$order->id.'-'.strtoupper(Str::random(12));

        $order->payments()->create([
            'user_id' => auth()->id(),
            'method' => $data['payment_method'],
            'amount' => $total,
            'fee' => $fee,
            'currency' => 'GHS',
            'status' => 'pending',
            'provider_reference' => $reference,
        ]);

        $cart->items()->delete();

        if ($data['payment_method'] === 'cod') {
            $order->update(['status' => 'confirmed']);

            return redirect()->route('order.confirmation', $order)
                ->with('status', 'Order placed successfully. Pay on delivery.');
        }

        $paystack = new PaystackService();

        if (! $paystack->isConfigured()) {
            $order->update(['status' => 'cancelled', 'payment_status' => 'failed']);

            return redirect()->route('order.confirmation', $order)
                ->withErrors(['payment' => 'The payment gateway is not configured.']);
        }

        $response = $paystack->initialize(
            auth()->user()->email,
            $total,
            $reference,
            ['order_id' => $order->id]
        );

        if (! empty($response['data']['authorization_url'])) {
            return redirect()->away($response['data']['authorization_url']);
        }

        $order->update(['status' => 'cancelled', 'payment_status' => 'failed']);

        return redirect()->route('order.confirmation', $order)
            ->withErrors(['payment' => 'Unable to initialize payment. Please try again.']);
    }

    public function confirmation(Order $order): View
    {
        $order->load(['items', 'payments', 'user']);

        return view('shop.order-confirmation', compact('order'));
    }
}
