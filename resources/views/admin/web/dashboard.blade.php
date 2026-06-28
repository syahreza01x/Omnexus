@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Dashboard Overview</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Ringkasan aktivitas platform dan metrik performa toko.</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
        <div class="absolute right-0 top-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-purple-50 dark:bg-purple-900/20 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna Aktif</p>
                <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $total_users }}</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
        <div class="absolute right-0 top-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-blue-50 dark:bg-blue-900/20 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $total_products }}</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Stat Card 3 -->
    <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
        <div class="absolute right-0 top-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-emerald-50 dark:bg-emerald-900/20 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transaksi</p>
                <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $total_transactions }}</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Custom Order Stats -->
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Statistik Custom Order</h2>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        <!-- Stat: Total -->
        <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Custom Order</p>
            <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $total_custom_orders }}</p>
        </div>
        <!-- Stat: Menunggu Review -->
        <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-500">Menunggu Review</p>
            <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $custom_orders_waiting_review }}</p>
        </div>
        <!-- Stat: Diproses -->
        <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
            <p class="text-sm font-medium text-blue-600 dark:text-blue-500">Diproses</p>
            <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $custom_orders_processing }}</p>
        </div>
        <!-- Stat: Produksi -->
        <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-500">Produksi</p>
            <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $custom_orders_production }}</p>
        </div>
        <!-- Stat: Selesai -->
        <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
            <p class="text-sm font-medium text-green-600 dark:text-green-500">Selesai</p>
            <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $custom_orders_completed }}</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Aksi Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <a href="{{ route('admin.web.custom-orders.index') }}" class="group flex flex-col p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-violet-500 dark:hover:border-violet-500 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg shadow-violet-500/10">
            <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <span class="font-medium text-gray-900 dark:text-gray-100">Kelola Custom Order</span>
            <span class="text-xs text-gray-500 mt-1">Review & update status custom order</span>
        </a>

        <a href="{{ route('admin.web.products.create') }}" class="group flex flex-col p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg shadow-purple-500/10">
            <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <span class="font-medium text-gray-900 dark:text-gray-100">Tambah Produk</span>
            <span class="text-xs text-gray-500 mt-1">Publikasikan katalog baru</span>
        </a>

        <a href="{{ route('admin.web.products') }}" class="group flex flex-col p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg shadow-blue-500/10">
            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
            <span class="font-medium text-gray-900 dark:text-gray-100">Kelola Produk</span>
            <span class="text-xs text-gray-500 mt-1">Edit & hapus data produk</span>
        </a>

        <a href="{{ route('admin.web.users') }}" class="group flex flex-col p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg shadow-emerald-500/10">
            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <span class="font-medium text-gray-900 dark:text-gray-100">Daftar Pengguna</span>
            <span class="text-xs text-gray-500 mt-1">Manajemen akun pelanggan</span>
        </a>

        <a href="{{ route('admin.web.transactions') }}" class="group flex flex-col p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg shadow-orange-500/10">
            <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <span class="font-medium text-gray-900 dark:text-gray-100">Pesanan Masuk</span>
            <span class="text-xs text-gray-500 mt-1">Cek seluruh transaksi</span>
        </a>
    </div>
</div>

<!-- Recent Transactions -->
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 shadow-sm overflow-hidden">
    <div class="border-b border-gray-100 dark:border-gray-700 px-6 py-5 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Transaksi Terbaru</h2>
        <a href="{{ route('admin.web.transactions') }}" class="text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300">Lihat Semua &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white dark:bg-gray-800 text-sm text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-6 py-4 font-medium">Order ID</th>
                    <th class="px-6 py-4 font-medium">Pelanggan</th>
                    <th class="px-6 py-4 font-medium">Total Harga</th>
                    <th class="px-6 py-4 font-medium">Status Pesanan</th>
                    <th class="px-6 py-4 font-medium">Tanggal Masuk</th>
                    <th class="px-6 py-4 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($recent_transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">#TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-xs font-bold text-purple-600 dark:text-purple-400">
                                    {{ substr($transaction->user->name, 0, 1) }}
                                </div>
                                {{ $transaction->user->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$transaction->status" type="transaction" />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $transaction->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.web.transactions.show', $transaction) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Belum Ada Transaksi</p>
                                <p class="text-sm text-gray-500 mt-1">Transaksi pelanggan akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
