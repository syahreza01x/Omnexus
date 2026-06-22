@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Warehouse Dashboard</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola pergerakan stok barang dan inventaris toko.</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-all duration-300">
        <div class="absolute right-0 top-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-blue-50 dark:bg-blue-900/20 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Jenis Produk</p>
                <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ $total_products }}</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="group relative overflow-hidden rounded-2xl bg-red-50 dark:bg-red-900/10 p-6 shadow-sm ring-1 ring-red-200 dark:ring-red-900/30 hover:shadow-md hover:ring-red-300 dark:hover:ring-red-800 transition-all duration-300">
        <div class="absolute right-0 top-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-red-100 dark:bg-red-900/20 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-red-700 dark:text-red-400">Peringatan: Stok Menipis (≤10)</p>
                <p class="text-4xl font-bold text-red-700 dark:text-red-400 mt-2">{{ $low_stock_products->count() }}</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-red-200/50 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Aksi Cepat</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl">
        <a href="{{ route('admin.warehouse.stocks') }}" class="group flex flex-col p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg shadow-indigo-500/10">
            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            </div>
            <span class="font-medium text-gray-900 dark:text-gray-100">Kelola Stok Barang</span>
            <span class="text-xs text-gray-500 mt-1">Tambah atau kurangi inventaris</span>
        </a>

        <a href="{{ route('admin.warehouse.logs') }}" class="group flex flex-col p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg shadow-blue-500/10">
            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="font-medium text-gray-900 dark:text-gray-100">History Perubahan</span>
            <span class="text-xs text-gray-500 mt-1">Lacak log keluar-masuk gudang</span>
        </a>
    </div>
</div>

<!-- Low Stock Products -->
@if ($low_stock_products->count() > 0)
    <div class="mb-8 rounded-2xl border border-red-200 bg-white dark:bg-gray-800 shadow-sm overflow-hidden dark:border-red-900/50">
        <div class="border-b border-red-100 dark:border-red-900/50 px-6 py-5 bg-red-50/50 dark:bg-red-900/10 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h2 class="text-lg font-bold text-red-700 dark:text-red-400">Peringatan: Stok Rendah</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white dark:bg-gray-800 text-sm text-gray-500 dark:text-gray-400 border-b border-red-100 dark:border-red-900/50">
                        <th class="px-6 py-4 font-medium">SKU</th>
                        <th class="px-6 py-4 font-medium">Nama Produk</th>
                        <th class="px-6 py-4 font-medium text-center">Sisa Stok</th>
                        <th class="px-6 py-4 font-medium">Satuan</th>
                        <th class="px-6 py-4 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-100 dark:divide-red-900/30">
                    @foreach ($low_stock_products as $product)
                        <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-colors">
                            <td class="px-6 py-4 text-sm font-mono text-gray-500 dark:text-gray-400">{{ $product->sku }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($product->unit) }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.warehouse.stocks.logs', $product) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Cek Riwayat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Recent Stock Changes -->
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 shadow-sm overflow-hidden">
    <div class="border-b border-gray-100 dark:border-gray-700 px-6 py-5 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Log Pergerakan Terakhir</h2>
        <a href="{{ route('admin.warehouse.logs') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Lihat Semua &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white dark:bg-gray-800 text-sm text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-6 py-4 font-medium">Nama Produk</th>
                    <th class="px-6 py-4 font-medium">Tipe Aksi</th>
                    <th class="px-6 py-4 font-medium">Jumlah</th>
                    <th class="px-6 py-4 font-medium">Stok Awal</th>
                    <th class="px-6 py-4 font-medium">Stok Akhir</th>
                    <th class="px-6 py-4 font-medium">Penanggung Jawab</th>
                    <th class="px-6 py-4 font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($recent_stock_logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->product->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <x-status-badge :status="$log->action" type="action" />
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <x-quantity-change :value="$log->quantity_change" />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $log->stock_before }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $log->stock_after }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ substr($log->changedBy->name, 0, 1) }}
                                </div>
                                {{ $log->changedBy->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Gudang Sedang Tenang</p>
                                <p class="text-sm text-gray-500 mt-1">Belum ada riwayat pergerakan stok yang tercatat.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
