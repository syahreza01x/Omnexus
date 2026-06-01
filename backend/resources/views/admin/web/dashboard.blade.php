@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Admin Web Dashboard</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola produk, user, dan transaksi</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $total_users }}</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $total_products }}</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transaksi</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $total_transactions }}</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
    <a href="{{ route('admin.web.products.create') }}" class="rounded-xl bg-purple-600 px-6 py-3 text-white font-medium hover:bg-purple-700 inline-block">
        Tambah Produk Baru
    </a>
    <a href="{{ route('admin.web.products') }}" class="rounded-xl bg-gray-600 px-6 py-3 text-white font-medium hover:bg-gray-700 inline-block">
        Kelola Produk
    </a>
    <a href="{{ route('admin.web.users') }}" class="rounded-xl bg-gray-600 px-6 py-3 text-white font-medium hover:bg-gray-700 inline-block">
        List User
    </a>
    <a href="{{ route('admin.web.transactions') }}" class="rounded-xl bg-gray-600 px-6 py-3 text-white font-medium hover:bg-gray-700 inline-block">
        List Transaksi
    </a>
</div>

<!-- Recent Transactions -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Transaksi Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">User</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Total</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($recent_transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $transaction->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $transaction->user->name }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <x-status-badge :status="$transaction->status" type="transaction" />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $transaction->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.web.transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
