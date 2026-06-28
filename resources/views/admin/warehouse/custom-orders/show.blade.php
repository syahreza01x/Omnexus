@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center gap-3">
    <a href="{{ route('admin.warehouse.custom-orders.index') }}" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:bg-gray-150 dark:hover:bg-gray-700 transition duration-150">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Detail Custom Order Produksi</h1>
        <p class="text-gray-550 dark:text-gray-400 mt-1">#ORD-{{ str_pad($customOrder->id, 5, '0', STR_PAD_LEFT) }} (Read-only)</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Order details --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 space-y-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-3">
                Spesifikasi Pesanan
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Kategori Pesanan</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white mt-1 block capitalize">
                        {{ $customOrder->category }}
                    </span>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Produk</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white mt-1 block">
                        @if($customOrder->product === 'lainnya')
                            {{ $customOrder->other_product_name }} <span class="text-xs text-gray-400">(Lainnya)</span>
                        @else
                            {{ $customOrder->product }}
                        @endif
                    </span>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Jumlah</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white mt-1 block font-mono">
                        {{ number_format($customOrder->quantity) }} Pcs
                    </span>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Warna</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white mt-1 block">
                        {{ $customOrder->color }}
                    </span>
                </div>
                
                @if($customOrder->category === 'konveksi')
                    <div>
                        <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Ukuran</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white mt-1 block">
                            {{ $customOrder->size ?? '-' }}
                        </span>
                    </div>
                @endif

                <div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Jenis Bahan</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white mt-1 block">
                        {{ $customOrder->material_type }}
                    </span>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Teknik Produksi</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white mt-1 block">
                        {{ $customOrder->production_technique }}
                    </span>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Deadline</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white mt-1 block">
                        {{ $customOrder->deadline->format('d F Y') }}
                    </span>
                </div>
            </div>

            @if($customOrder->notes)
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-150 dark:border-gray-700">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1">Catatan Tambahan</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $customOrder->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar Customer Info & Status --}}
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 space-y-6">
            <div>
                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">Status Saat Ini</span>
                <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-semibold {{ $customOrder->status_badge_classes }} shadow-sm">
                    {{ $customOrder->status_label }}
                </span>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">
                    Informasi Pelanggan
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs text-gray-400 block">Nama Pemesan</span>
                        <span class="font-medium text-gray-800 dark:text-gray-255">{{ $customOrder->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Nama Akun</span>
                        <span class="font-medium text-gray-600 dark:text-gray-400">{{ $customOrder->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Nomor WhatsApp</span>
                        <span class="font-medium text-gray-800 dark:text-gray-255">{{ $customOrder->whatsapp_number }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
