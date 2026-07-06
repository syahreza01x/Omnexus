@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center gap-3">
    <a href="{{ route('admin.web.custom-orders.index') }}" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:bg-gray-150 dark:hover:bg-gray-700 transition duration-150">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Detail Custom Order</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">#ORD-{{ str_pad($customOrder->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-900/50 dark:text-green-400">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Order details --}}
    <div class="lg:col-span-2 space-y-6">
        @if($customOrder->status === 'revisi_desain' && $customOrder->revision_notes)
            <div class="bg-red-50 border border-red-200 dark:bg-red-950/20 dark:border-red-900/50 rounded-2xl p-6 space-y-3">
                <div class="flex items-center gap-2.5 text-red-700 dark:text-red-400">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h4 class="font-bold text-md">Permintaan Revisi dari Customer (Revisi ke-{{ $customOrder->revision_count }})</h4>
                </div>
                <div class="text-sm text-red-850 dark:text-red-300 bg-white/50 dark:bg-gray-955/30 p-4 rounded-xl font-medium border border-red-105 dark:border-red-950">
                    {{ $customOrder->revision_notes }}
                </div>
                <p class="text-xs text-red-650 dark:text-red-400 font-semibold">Silakan perbaiki mockup desain dan unggah ulang berkas mockup yang baru di kolom sebelah kanan untuk mengirimkan draf perbaikan ke customer.</p>
            </div>
        @endif

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
                    <span class="text-sm font-medium text-gray-900 dark:text-white mt-1 block">
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

    {{-- Sidebar actions (Status Change & Customer Info) --}}
    <div class="space-y-6">
        {{-- Change Status Card --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 space-y-4">
            <h3 class="text-md font-bold text-gray-900 dark:text-white mb-2">
                Kelola Pesanan & Status
            </h3>
            
            <form action="{{ route('admin.web.custom-orders.update', $customOrder) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PATCH')
                
                <div>
                    <label for="status" class="block text-xs text-gray-400 mb-2 uppercase tracking-wide">Status Saat Ini</label>
                    <select name="status" id="status" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-purple-500 focus:outline-none">
                        @foreach ($statuses as $val => $label)
                            <option value="{{ $val }}" {{ $customOrder->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="border-t border-gray-150 dark:border-gray-700 pt-3">
                    <label for="admin_mockup_file" class="block text-xs text-gray-400 mb-2 uppercase tracking-wide">Unggah Mockup Desain (Visual/PDF)</label>
                    <input type="file" name="admin_mockup_file" id="admin_mockup_file" class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 dark:file:bg-purple-900/30 dark:file:text-purple-400 hover:file:bg-purple-100 transition">
                    <p class="text-[10px] text-gray-450 mt-1">Mengunggah mockup baru akan otomatis mengubah status menjadi 'Menunggu Persetujuan Customer'.</p>
                </div>

                @if($customOrder->admin_mockup_file)
                    <div class="p-3 bg-gray-50 dark:bg-gray-900/30 rounded-xl border border-gray-150 dark:border-gray-700 flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <span class="text-[10px] text-gray-400 block">Mockup Terunggah</span>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 truncate block">{{ basename($customOrder->admin_mockup_file) }}</span>
                        </div>
                        <a href="{{ asset('storage/' . $customOrder->admin_mockup_file) }}" target="_blank" class="text-xs font-bold text-purple-600 hover:text-purple-700">Lihat</a>
                    </div>
                @endif
                
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl text-sm transition duration-150 shadow-md">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Customer Info & Files Card --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 space-y-6">
            <div>
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
                        <span class="text-xs text-gray-400 block">Email</span>
                        <span class="font-medium text-gray-800 dark:text-gray-255">{{ $customOrder->user->email }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Nomor WhatsApp</span>
                        <span class="font-medium text-gray-850 dark:text-gray-255">{{ $customOrder->whatsapp_number }}</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">
                    File Desain
                </h3>
                @if ($customOrder->design_file)
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-150 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            @php
                                $fileName = basename($customOrder->design_file);
                            @endphp
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 truncate" title="{{ $fileName }}">
                                {{ $fileName }}
                            </p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Siap Diunduh</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.web.custom-orders.download', $customOrder) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl text-sm transition duration-150 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh File Desain
                    </a>
                @else
                    <p class="text-xs text-red-500 font-semibold">Tidak ada file desain yang diunggah.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
