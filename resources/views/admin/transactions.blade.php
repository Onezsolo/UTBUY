@extends('layouts.admin', ['active' => 'transactions'])

@section('title', 'Admin Transactions - Grocery')

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

    <header class="bg-white shadow-sm px-6 py-4 sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800">Transactions</h1>
    </header>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Total Revenue</div><div class="text-xl font-bold text-admin">₵{{ number_format($stats['revenue'], 2) }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Refunds</div><div class="text-xl font-bold text-red-500">₵{{ number_format($stats['refunds'], 2) }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Pending</div><div class="text-xl font-bold text-yellow-500">₵{{ number_format($stats['pending'], 2) }}</div></div>
            <div class="bg-white rounded-xl card-shadow p-4"><div class="text-sm text-gray-500">Avg. Order Value</div><div class="text-xl font-bold text-green-500">₵{{ number_format($stats['average'], 2) }}</div></div>
        </div>

        <div class="bg-white rounded-xl card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500 bg-gray-50">
                            <th class="p-4 font-medium">Txn ID</th>
                            <th class="p-4 font-medium">Order</th>
                            <th class="p-4 font-medium">Customer</th>
                            <th class="p-4 font-medium">Method</th>
                            <th class="p-4 font-medium">Date</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium text-right">Amount</th>
                            <th class="p-4 font-medium text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($payments as $payment)
                            @php $isRefund = $payment->status === 'refunded'; @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium text-admin">{{ $payment->provider_reference ?? 'TXN-'.$payment->id }}</td>
                                <td class="p-4">{{ $payment->order->order_number ?? '—' }}</td>
                                <td class="p-4">{{ $payment->user->name ?? '—' }}</td>
                                <td class="p-4">
                                    @if ($payment->method === 'paystack')
                                        <i class="fas fa-credit-card text-blue-600 mr-1"></i> Paystack
                                    @else
                                        <i class="fas fa-money-bill-wave text-green-500 mr-1"></i> Cash on Delivery
                                    @endif
                                </td>
                                <td class="p-4 text-gray-600">{{ $payment->created_at->format('M d, Y') }}</td>
                                <td class="p-4"><span class="{{ $paymentStatusColors[$payment->status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucfirst($payment->status) }}</span></td>
                                <td class="p-4 text-right font-bold {{ $isRefund ? 'text-green-500' : 'text-red-500' }}">{{ $isRefund ? '+' : '-' }}₵{{ number_format((float) $payment->amount, 2) }}</td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if (in_array($payment->status, ['pending', 'authorized', 'failed']))
                                        <form method="POST" action="{{ route('admin.transactions.approve', $payment) }}" class="inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="text-green-600 text-sm hover:underline mr-2" title="Approve"><i class="fas fa-check"></i></button>
                                        </form>
                                    @endif
                                    @if ($payment->status === 'paid')
                                        <form method="POST" action="{{ route('admin.transactions.refund', $payment) }}" class="inline" onsubmit="return confirm('Refund this payment?');">
                                            @csrf @method('PUT')
                                            <button type="submit" class="text-yellow-600 text-sm hover:underline" title="Refund"><i class="fas fa-undo"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="p-10 text-center text-gray-400">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200">{{ $payments->links() }}</div>
        </div>
    </div>
@endsection
