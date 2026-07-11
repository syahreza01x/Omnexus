@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Kelola Custom Order</h1>
        <p class="text-gray-550 dark:text-gray-400 mt-1">Review spesifikasi, kelola status, dan unduh berkas desain pelanggan.</p>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 shadow-sm overflow-hidden mb-8">
    {{-- Search & Filter Section --}}
    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
        <form method="GET" action="{{ route('admin.web.custom-orders.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Cari nama, email, produk, atau WhatsApp..." value="{{ request('search') }}" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-purple-500 focus:outline-none">
            </div>
            
            <div class="w-full md:w-64">
                <select name="status" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-purple-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    @foreach ($statuses as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl text-sm transition duration-150 shadow-md shadow-purple-500/10">
                    Cari & Filter
                </button>
                @if (request('search') || request('status'))
                    <a href="{{ route('admin.web.custom-orders.index') }}" class="w-full md:w-auto px-5 py-2.5 border border-gray-250 dark:border-gray-700 text-gray-700 dark:text-gray-355 font-semibold rounded-xl text-sm hover:bg-gray-50 dark:hover:bg-gray-750 transition duration-150">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-sm font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <th class="px-6 py-4">ID Order</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Jumlah</th>
                    <th class="px-6 py-4">Deadline</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Desain</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-150 dark:divide-gray-700">
                @forelse ($customOrders as $order)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750/30 transition duration-150">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                            @if($order->product_id)
                                #CUST-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            @else
                                #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $order->user->name }}
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $order->whatsapp_number ?? $order->user->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium">
                                @if($order->product_id)
                                    {{ $order->product_relation->name ?? 'Custom Product' }}
                                @else
                                    @if($order->product === 'lainnya')
                                        {{ $order->other_product_name }}
                                    @else
                                        {{ $order->product }}
                                    @endif
                                @endif
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 capitalize">
                                {{ $order->category }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $order->quantity }} Pcs
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $order->deadline ? $order->deadline->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_badge_classes }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($order->design_file || $order->user_design_path)
                                <a href="{{ route('admin.web.custom-orders.download', $order) }}" class="inline-flex items-center gap-1 text-xs text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-semibold" title="Unduh Desain">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Unduh
                                </a>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.web.custom-orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/40 rounded-xl text-xs font-bold transition duration-150">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Tidak ada Custom Order</p>
                                <p class="text-sm mt-1">Data custom order pelanggan akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($customOrders->hasPages())
        <div class="border-t border-gray-100 px-6 py-4 dark:border-gray-700">
            {{ $customOrders->links() }}
        </div>
    @endif
</div>
@endsection
