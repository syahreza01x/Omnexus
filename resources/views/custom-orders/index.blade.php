<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pesanan Custom Saya
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-zinc-800">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold font-playfair">Pesanan Custom Saya</h2>
                    <a href="{{ route('beranda') }}" class="text-sm text-violet-600 hover:text-violet-500 font-semibold">
                        Buat Pesanan Baru
                    </a>
                </div>

                @if($orders->isEmpty())
                    <div class="text-center py-12 bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-dashed border-gray-200 dark:border-zinc-700">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum Ada Pesanan Custom</h3>
                        <p class="text-gray-500 dark:text-gray-400">Anda belum pernah membuat pesanan custom.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-zinc-700">
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-500 dark:text-gray-400">ID Pesanan</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-500 dark:text-gray-400">Produk Basis</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-500 dark:text-gray-400">Jumlah</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-500 dark:text-gray-400">Status</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-gray-500 dark:text-gray-400">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                        <td class="py-4 px-4">
                                            <span class="font-mono text-sm font-semibold">#CUST-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                            <div class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('d M Y H:i') }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="font-medium">{{ $order->product->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->category ?? '-' }} | {{ $order->material ?? '-' }}</div>
                                        </td>
                                        <td class="py-4 px-4">{{ $order->quantity }} pcs</td>
                                        <td class="py-4 px-4">
                                            @if($order->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500">Menunggu Respon</span>
                                            @elseif($order->status === 'designing')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500">Sedang Didesain</span>
                                            @elseif($order->status === 'revision')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-500">Revisi</span>
                                            @elseif($order->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500">Disetujui (Menunggu Bayar)</span>
                                            @elseif($order->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Selesai (Sudah Checkout)</span>
                                            @elseif($order->status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <a href="{{ route('custom-orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400 hover:bg-violet-100 dark:hover:bg-violet-500/20 rounded-lg text-sm font-medium transition-colors">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
