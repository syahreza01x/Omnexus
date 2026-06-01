@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Detail Transaksi #{{ $transaction->id }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">User: {{ $transaction->user->name }}</p>
    </div>
    <a href="{{ route('admin.web.transactions') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">← Kembali</a>
</div>

<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
        <p class="mt-2">
            <x-status-badge :status="$transaction->status" type="transaction" />
        </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal</p>
        <p class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $transaction->created_at->format('d M Y') }}</p>
    </div>
</div>

<!-- Update Status -->
<form method="POST" action="{{ route('admin.web.transactions.update-status', $transaction) }}" class="mb-8 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
    @csrf
    @method('PATCH')
    <label class="block mb-4 text-sm font-medium text-gray-700 dark:text-gray-300">Ubah Status</label>
    <div class="flex gap-4">
        <select name="status" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
            <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ $transaction->status === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="processing" {{ $transaction->status === 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ $transaction->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="rounded-lg bg-purple-600 px-6 py-2 text-white font-medium hover:bg-purple-700">Update Status</button>
    </div>
</form>

<!-- Items -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Produk dalam Transaksi</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Produk</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Qty</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Harga Satuan</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($transaction->items as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->product->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->quantity }} {{ $item->product->unit }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
