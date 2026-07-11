@extends('admin.layouts.app')

@section('content')
<div class="px-6 py-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Custom Orders</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola permintaan custom order dari pelanggan.</p>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl dark:bg-green-900/20 dark:border-green-900 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400 font-medium border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Produk Basis</th>
                            <th class="px-6 py-4">Spesifikasi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tgl Dibuat</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white">
                                    #CUST-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset($order->product->image_path ?: 'images/items/1.png') }}" class="w-8 h-8 rounded object-cover border dark:border-gray-700">
                                        <span class="font-medium text-gray-900 dark:text-white truncate max-w-[150px]">{{ $order->product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500">Qty: {{ $order->quantity }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[150px]">{{ $order->category ?? '-' }} | {{ $order->material ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500', 'Pending'],
                                            'designing' => ['bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500', 'Desain'],
                                            'revision' => ['bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-500', 'Revisi'],
                                            'approved' => ['bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500', 'Disetujui'],
                                            'completed' => ['bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300', 'Selesai'],
                                            'rejected' => ['bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500', 'Ditolak'],
                                        ];
                                        $conf = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold tracking-wider uppercase {{ $conf[0] }}">
                                        {{ $conf[1] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.web.custom-orders.show', $order) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition-colors dark:bg-purple-900/20 dark:text-purple-400 dark:hover:bg-purple-900/40">
                                        Detail & Chat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data custom order.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
