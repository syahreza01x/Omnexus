@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Custom Order Produksi</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-1">Daftar custom order yang sedang berada dalam proses produksi atau siap untuk diambil/dikirim.</p>
</div>

<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 shadow-sm overflow-hidden">
    {{-- Search Bar --}}
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
        <form method="GET" action="{{ route('admin.warehouse.custom-orders.index') }}" class="flex gap-4">
            <input type="text" name="search" placeholder="Cari nama pelanggan atau nama produk..." value="{{ request('search') }}" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="rounded-lg bg-indigo-600 hover:bg-indigo-750 text-white px-6 py-2 font-semibold text-sm transition">Cari</button>
            @if(request('search'))
                <a href="{{ route('admin.warehouse.custom-orders.index') }}" class="rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900 text-sm font-semibold text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-4">ID Order</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Jumlah</th>
                    <th class="px-6 py-4">Deadline</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-300">
                @forelse ($customOrders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750/30 transition">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                            #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 font-medium">
                            {{ $order->user->name }}
                        </td>
                        <td class="px-6 py-4 capitalize">
                            {{ $order->category }}
                        </td>
                        <td class="px-6 py-4">
                            @if($order->product === 'lainnya')
                                {{ $order->other_product_name }}
                            @else
                                {{ $order->product }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->quantity }} Pcs
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $order->deadline->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_badge_classes }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.warehouse.custom-orders.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-xl text-xs font-bold transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Tidak ada Custom Order</p>
                                <p class="text-sm mt-1">Saat ini tidak ada pesanan yang sedang diproduksi atau siap serah terima.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($customOrders->hasPages())
        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
            {{ $customOrders->links() }}
        </div>
    @endif
</div>
@endsection
