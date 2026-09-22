<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaystackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private PaystackService $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('reference') ?? $request->query('trxref');
        $payment = Payment::where('provider_reference', $reference)->first();

        if (! $payment) {
            return redirect()->route('home')->withErrors(['payment' => 'Payment record not found.']);
        }

        if (in_array($payment->status, ['paid', 'refunded'])) {
            return redirect()->route('order.confirmation', $payment->order);
        }

        $response = $this->paystack->verify($reference);
        $status = $response['data']['status'] ?? null;

        if ($status === 'success') {
            $this->markPaid($payment);

            return redirect()->route('order.confirmation', $payment->order)
                ->with('status', 'Payment successful.');
        }

        $this->markFailed($payment);

        return redirect()->route('order.confirmation', $payment->order)
            ->withErrors(['payment' => 'Payment was not successful.']);
    }

    public function webhook(Request $request): JsonResponse
    {
        $signature = $request->header('x-paystack-signature');

        if (! $this->paystack->verifyWebhookSignature($request->getContent(), $signature)) {
            return response()->json(['status' => 'invalid signature'], 400);
        }

        if ($request->input('event') !== 'charge.success') {
            return response()->json(['status' => 'ignored']);
        }

        $reference = $request->input('data.reference');
        $payment = Payment::where('provider_reference', $reference)->first();

        if ($payment && ! in_array($payment->status, ['paid', 'refunded'])) {
            $this->markPaid($payment);
        }

        return response()->json(['status' => 'processed']);
    }

    private function markPaid(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'paid', 'paid_at' => now()]);

            if ($payment->order) {
                $payment->order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                ]);
            }
        });
    }

    private function markFailed(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'failed']);

            if ($payment->order) {
                $payment->order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);
            }
        });
    }
}
