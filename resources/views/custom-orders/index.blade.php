<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 dark:from-purple-400 dark:via-violet-400 dark:to-indigo-400 bg-clip-text text-transparent leading-tight">
                    {{ __('Custom Order Saya') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola dan pantau seluruh pesanan pakaian custom Anda di sini.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-350 font-semibold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('custom-orders.export') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-lg shadow-emerald-500/20 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh CSV
                </a>
                <a href="{{ route('custom-orders.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-lg shadow-purple-500/25 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Order Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 dark:bg-gray-955/20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Toast Notification --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-900/50 dark:text-emerald-400 flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-emerald-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-800 transition-all duration-300 hover:shadow-2xl">
                <div class="p-6 md:p-8">
                    <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-800">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/70 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-800 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                    <th class="px-6 py-4">ID Pesanan</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4">Produk</th>
                                    <th class="px-6 py-4">Jumlah</th>
                                    <th class="px-6 py-4">Deadline</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($customOrders as $order)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-all duration-150">
                                        <td class="px-6 py-5 font-mono text-sm font-bold text-gray-900 dark:text-white">
                                            @if($order->product_id)
                                                #CUST-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                            @else
                                                #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex px-3 py-1 rounded-xl text-[10px] font-bold uppercase tracking-wider {{ $order->category === 'konveksi' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                                {{ $order->category ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-sm font-semibold text-gray-850 dark:text-gray-250">
                                            @if($order->product_id)
                                                {{ $order->product_relation->name ?? 'Custom Product' }}
                                            @else
                                                @if($order->product === 'lainnya')
                                                    {{ $order->other_product_name }} <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">(Lainnya)</span>
                                                @else
                                                    {{ $order->product }}
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 text-sm font-semibold">
                                            {{ number_format($order->quantity) }} <span class="text-xs text-gray-400 font-normal">Pcs</span>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                            {{ $order->deadline ? $order->deadline->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex px-3 py-1 rounded-xl text-xs font-semibold {{ $order->status_badge_classes }} shadow-sm">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <a href="{{ route('custom-orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-purple-500/10 to-indigo-500/10 hover:from-purple-500/20 hover:to-indigo-500/20 text-purple-700 dark:text-purple-400 rounded-2xl text-xs font-bold transition duration-200 focus:outline-none">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                                <div class="w-16 h-16 rounded-3xl bg-purple-50 dark:bg-purple-950/30 flex items-center justify-center text-purple-600 dark:text-purple-400 mb-4 shadow-inner">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                                    </svg>
                                                </div>
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Custom Order</h3>
                                                <p class="text-sm mt-2 text-gray-500">Anda belum pernah membuat custom order pakaian atau merchandise. Pesan produk custom impian Anda sekarang!</p>
                                                <a href="{{ route('custom-orders.create') }}" class="mt-6 inline-flex items-center gap-1.5 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-semibold rounded-2xl transition duration-200 shadow-md focus:outline-none">
                                                    Buat Custom Order Sekarang
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($customOrders->hasPages())
                        <div class="mt-6">
                            {{ $customOrders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
