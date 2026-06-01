@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Admin Gudang Dashboard</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola stok barang dan history perubahan</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $total_products }}</p>
    </div>
    <div class="rounded-xl border border-red-200 bg-red-50 p-6 dark:border-red-900 dark:bg-red-950/40">
        <p class="text-sm font-medium text-red-700 dark:text-red-400">Stok Rendah (≤10)</p>
        <p class="text-3xl font-bold text-red-700 dark:text-red-400 mt-2">{{ $low_stock_products->count() }}</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <a href="{{ route('admin.warehouse.stocks') }}" class="rounded-xl bg-purple-600 px-6 py-3 text-white font-medium hover:bg-purple-700 inline-block">
        Kelola Stok
    </a>
    <a href="{{ route('admin.warehouse.logs') }}" class="rounded-xl bg-gray-600 px-6 py-3 text-white font-medium hover:bg-gray-700 inline-block ml-4">
        History Perubahan Stok
    </a>
</div>

<!-- Low Stock Products -->
@if ($low_stock_products->count() > 0)
    <div class="mb-8 rounded-xl border border-red-200 bg-red-50 dark:border-red-900 dark:bg-red-950/40">
        <div class="border-b border-red-200 px-6 py-4 dark:border-red-900">
            <h2 class="text-lg font-semibold text-red-700 dark:text-red-400">Produk dengan Stok Rendah</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-red-200 bg-red-100 dark:border-red-900 dark:bg-red-950">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-red-700 dark:text-red-400">SKU</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-red-700 dark:text-red-400">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-red-700 dark:text-red-400">Stok Saat Ini</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-red-700 dark:text-red-400">Satuan</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-red-700 dark:text-red-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-200 dark:divide-red-900">
                    @foreach ($low_stock_products as $product)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-gray-700 dark:text-gray-300">{{ $product->sku }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-red-700 dark:text-red-400">{{ $product->stock }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($product->unit) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.warehouse.stocks.logs', $product) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Lihat History</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Recent Stock Changes -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Perubahan Stok Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Produk</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Perubahan</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Stok Sebelum</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Stok Sesudah</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Oleh</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($recent_stock_logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->product->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <x-status-badge :status="$log->action" type="action" />
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <x-quantity-change :value="$log->quantity_change" />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $log->stock_before }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $log->stock_after }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $log->changedBy->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada perubahan stok</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
