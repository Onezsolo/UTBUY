@extends('layouts.user', ['active' => 'transactions'])

@section('title', 'Transactions - Grocery')

@section('page')
    @php
        $paymentStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'authorized' => 'bg-blue-100 text-blue-700',
            'paid' => 'bg-green-100 text-green-700',
            'failed' => 'bg-red-100 text-red-700',
            'refunded' => 'bg-green-100 text-green-700',
        ];
    @endphp

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Transaction History</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl card-shadow p-5">
            <div class="text-sm text-gray-500 mb-1">Total Spent</div>
            <div class="text-2xl font-bold text-red-500">₵{{ number_format($stats['spent'], 2) }}</div>
        </div>
        <div class="bg-white rounded-xl card-shadow p-5">
            <div class="text-sm text-gray-500 mb-1">Total Refunds</div>
            <div class="text-2xl font-bold text-green-500">₵{{ number_format($stats['refunds'], 2) }}</div>
        </div>
        <div class="bg-white rounded-xl card-shadow p-5">
            <div class="text-sm text-gray-500 mb-1">Pending</div>
            <div class="text-2xl font-bold text-orange-500">₵{{ number_format($stats['pending'], 2) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500 bg-gray-50">
                        <th class="p-4 font-medium">Transaction ID</th>
                        <th class="p-4 font-medium">Date</th>
                        <th class="p-4 font-medium">Description</th>
                        <th class="p-4 font-medium">Method</th>
                        <th class="p-4 font-medium">Status</th>
                        <th class="p-4 font-medium text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($payments as $payment)
                        @php $isRefund = $payment->status === 'refunded'; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium text-primary">{{ $payment->provider_reference ?? 'TXN-'.$payment->id }}</td>
                            <td class="p-4 text-gray-600">{{ $payment->created_at->format('M d, Y') }}</td>
                            <td class="p-4">{{ $payment->order->order_number ?? 'Payment' }}</td>
                            <td class="p-4">{{ $payment->method === 'paystack' ? 'Paystack' : 'Cash on Delivery' }}</td>
                            <td class="p-4"><span class="{{ $paymentStatusColors[$payment->status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucfirst($payment->status) }}</span></td>
                            <td class="p-4 text-right font-bold {{ $isRefund ? 'text-green-500' : 'text-red-500' }}">{{ $isRefund ? '+' : '-' }}₵{{ number_format((float) $payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-10 text-center text-gray-400">No transactions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200">{{ $payments->links() }}</div>
    </div>
@endsection
