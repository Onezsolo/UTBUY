<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $stats = [
            'revenue' => (float) Payment::where('status', 'paid')->sum('amount'),
            'refunds' => (float) Payment::where('status', 'refunded')->sum('amount'),
            'pending' => (float) Payment::where('status', 'pending')->sum('amount'),
            'average' => (float) Payment::where('status', 'paid')->avg('amount'),
        ];

        $payments = Payment::with(['order', 'user'])->latest()->paginate(20);

        return view('admin.transactions', compact('payments', 'stats'));
    }

    public function approve(Request $request, Payment $payment): RedirectResponse
    {
        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'paid', 'paid_at' => now()]);

            if ($payment->order) {
                $payment->order->update(['payment_status' => 'paid']);
            }
        });

        return back()->with('status', 'Payment approved.');
    }

    public function refund(Request $request, Payment $payment): RedirectResponse
    {
        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'refunded']);

            if ($payment->order) {
                $payment->order->update(['payment_status' => 'refunded']);
            }
        });

        return back()->with('status', 'Payment refunded.');
    }

    public function markFailed(Request $request, Payment $payment): RedirectResponse
    {
        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'failed']);

            if ($payment->order) {
                $payment->order->update(['payment_status' => 'failed']);
            }
        });

        return back()->with('status', 'Payment marked as failed.');
    }
}
