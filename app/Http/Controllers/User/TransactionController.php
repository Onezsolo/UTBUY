<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $stats = [
            'spent' => (float) Payment::where('user_id', $userId)->where('status', 'paid')->sum('amount'),
            'refunds' => (float) Payment::where('user_id', $userId)->where('status', 'refunded')->sum('amount'),
            'pending' => (float) Payment::where('user_id', $userId)->where('status', 'pending')->sum('amount'),
        ];

        $payments = Payment::where('user_id', $userId)
            ->with('order')
            ->latest()
            ->paginate(20);

        return view('user.transactions', compact('payments', 'stats'));
    }
}
