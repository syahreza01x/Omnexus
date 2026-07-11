@extends('admin.layouts.app')

@section('content')
    @if(!$customOrder->product_id)
        {{-- ─── ARDIMAN'S STANDALONE ADMIN LAYOUT ─── --}}
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
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-550 uppercase tracking-wider block">Ukuran</span>
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
                                {{ $customOrder->deadline ? $customOrder->deadline->format('d F Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    @if($customOrder->notes)
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-150 dark:border-gray-700">
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-555 uppercase tracking-wider block mb-1">Catatan Tambahan</span>
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

                        <div>
                            <label for="admin_mockup_file" class="block text-xs text-gray-400 mb-2 uppercase tracking-wide">Unggah / Ganti Mockup Desain</label>
                            <input type="file" name="admin_mockup_file" id="admin_mockup_file" class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 dark:file:bg-purple-900/30 dark:file:text-purple-400 hover:file:bg-purple-100 transition">
                            <p class="text-[10px] text-gray-450 mt-1">Mendukung: JPG, JPEG, PNG, PDF, AI, CDR. Maksimal 10MB.</p>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-purple-650 hover:bg-purple-700 text-white text-xs font-semibold rounded-xl uppercase tracking-wider transition duration-150 shadow-md">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                {{-- Customer Contact Card --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 space-y-4">
                    <h3 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-2">
                        Hubungi Pelanggan
                    </h3>
                    <div class="space-y-3 text-sm font-medium">
                        <div>
                            <span class="text-xs text-gray-400 block">Nama Akun</span>
                            <span class="text-gray-800 dark:text-gray-200 block mt-0.5">{{ $customOrder->user->name }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">WhatsApp Pemesan</span>
                            <span class="text-gray-800 dark:text-gray-200 block mt-0.5 font-mono">{{ $customOrder->whatsapp_number }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Email Terdaftar</span>
                            <span class="text-gray-800 dark:text-gray-200 block mt-0.5">{{ $customOrder->user->email }}</span>
                        </div>
                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customOrder->whatsapp_number) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl uppercase tracking-wider transition duration-150 shadow-md">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.03-5.114-2.905-6.989-1.874-1.873-4.351-2.903-6.985-2.904-5.442 0-9.866 4.42-9.87 9.865-.001 1.702.463 3.361 1.34 4.8l-.396 1.446 1.46-.382z"/></svg>
                                WhatsApp Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- ─── REZA'S BASIS PRODUCT ADMIN LAYOUT WITH CHAT ─── --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('admin.web.custom-orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pesanan #CUST-{{ str_pad($customOrder->id, 4, '0', STR_PAD_LEFT) }}</h1>
            </div>
            
            <form method="POST" action="{{ route('admin.web.custom-orders.update-status', $customOrder) }}" class="flex items-end gap-3 bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                @csrf
                @method('PATCH')
                
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status Pesanan</label>
                    <select name="status" class="rounded-lg border border-gray-350 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-1.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none">
                        <option value="pending" {{ $customOrder->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Respon)</option>
                        <option value="designing" {{ $customOrder->status === 'designing' ? 'selected' : '' }}>Desain (Proses Mockup)</option>
                        <option value="revision" {{ $customOrder->status === 'revision' ? 'selected' : '' }}>Revisi (Perlu Perubahan)</option>
                        <option value="approved" {{ $customOrder->status === 'approved' ? 'selected' : '' }}>Disetujui (Menunggu Pembayaran)</option>
                        <option value="completed" {{ $customOrder->status === 'completed' ? 'selected' : '' }}>Selesai (Sudah Checkout)</option>
                        <option value="rejected" {{ $customOrder->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                
                <div>
                    <label for="price_per_item" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Harga Satuan (Rp)</label>
                    <input type="number" name="price_per_item" value="{{ (int)$customOrder->price_per_item }}" placeholder="Masukkan harga satuan" class="rounded-lg border border-gray-350 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-1.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none w-44">
                </div>

                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider rounded-lg transition">
                    Update
                </button>
            </form>
        </div>

        {{-- Alert Toast / Success Message --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-250 text-green-700 dark:bg-green-950/20 dark:border-green-900/50 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{
            scrollToBottom() {
                const container = document.getElementById('chat-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        }" x-init="scrollToBottom()">
            {{-- Left Column: Details & Upload Mockup --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-md font-bold mb-4 border-b pb-2 dark:border-gray-700 text-gray-900 dark:text-white">Detail Permintaan</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wider">Pelanggan</div>
                            <div class="font-bold text-gray-900 dark:text-white mt-0.5">
                                {{ $customOrder->user->name }}
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $customOrder->user->email }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wider">Basis Pakaian</div>
                            <div class="font-medium flex items-center gap-2 mt-1.5 text-gray-900 dark:text-white">
                                @if($customOrder->product_relation)
                                    <img src="{{ asset($customOrder->product_relation->image_path ?: 'images/items/1.png') }}" class="w-8 h-8 rounded object-cover">
                                    {{ $customOrder->product_relation->name }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Kategori</div>
                                <div class="font-medium mt-0.5 text-gray-900 dark:text-white">{{ $customOrder->category ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Bahan</div>
                                <div class="font-medium mt-0.5 text-gray-900 dark:text-white">{{ $customOrder->material ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Jumlah</div>
                                <div class="font-medium mt-0.5 text-gray-900 dark:text-white">{{ $customOrder->quantity }} pcs</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Harga per Item</div>
                                <div class="font-bold text-violet-600 mt-0.5">
                                    {{ $customOrder->price_per_item ? 'Rp ' . number_format($customOrder->price_per_item, 0, ',', '.') : 'Belum ditentukan' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wider">Desain Awal (Dari Customer)</div>
                            @if($customOrder->user_design_path)
                                <a href="{{ asset('storage/' . $customOrder->user_design_path) }}" target="_blank" class="mt-2 block border rounded-xl overflow-hidden hover:opacity-80 transition-opacity">
                                    <img src="{{ asset('storage/' . $customOrder->user_design_path) }}" class="w-full h-auto object-cover max-h-48">
                                </a>
                            @else
                                <div class="font-medium mt-1">-</div>
                            @endif
                        </div>
                        
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wider">Catatan</div>
                            <div class="font-medium p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg text-sm mt-1 text-gray-800 dark:text-gray-200">
                                {{ $customOrder->notes ?: '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Admin Design Result --}}
                @if($customOrder->admin_design_path)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-violet-250 dark:border-violet-900/50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-3 bg-violet-100 dark:bg-violet-900/30 rounded-bl-xl text-violet-600 dark:text-violet-400 font-bold text-xs uppercase tracking-wider">
                            Hasil Desain
                        </div>
                        <h3 class="text-md font-bold mb-4 text-gray-900 dark:text-white">Preview Hasil Desain (Acc)</h3>
                        <a href="{{ asset('storage/' . $customOrder->admin_design_path) }}" target="_blank" class="block rounded-xl overflow-hidden shadow">
                            <img src="{{ asset('storage/' . $customOrder->admin_design_path) }}" class="w-full h-auto">
                        </a>
                    </div>
                @endif
            </div>

            {{-- Right Column: Chat / Revisions --}}
            <div class="lg:col-span-2 flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 h-[500px] lg:h-[650px]">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50 rounded-t-2xl">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Diskusi & Revisi Chat
                    </h3>
                    <div class="text-xs font-semibold px-3 py-1 rounded-full bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-300">
                        Status: {{ strtoupper($customOrder->status) }}
                    </div>
                </div>
                
                <div id="chat-container" class="flex-1 p-6 overflow-y-auto space-y-4">
                    @if($customOrder->revisions->isEmpty())
                        <div class="h-full flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p>Belum ada diskusi revisi.</p>
                        </div>
                    @else
                        @foreach($customOrder->revisions as $rev)
                            @if($rev->sender_type === 'admin')
                                {{-- Admin Message (Right) --}}
                                <div class="flex flex-col items-end">
                                    <div class="max-w-[80%] bg-violet-600 text-white rounded-2xl rounded-tr-sm p-4 shadow-sm">
                                        @if($rev->message)
                                            <p class="whitespace-pre-wrap text-sm mb-2">{{ $rev->message }}</p>
                                        @endif
                                        @if($rev->attachment_path)
                                            <a href="{{ asset('storage/' . $rev->attachment_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $rev->attachment_path) }}" class="rounded-lg max-h-48 object-cover border border-white/20">
                                            </a>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-400 mt-1 mr-1">{{ $rev->created_at->format('H:i, d M') }}</span>
                                </div>
                            @else
                                {{-- User Message (Left) --}}
                                <div class="flex flex-col items-start">
                                    <div class="max-w-[80%] bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                                        @if($rev->message)
                                            <p class="whitespace-pre-wrap text-sm mb-2">{{ $rev->message }}</p>
                                        @endif
                                        @if($rev->attachment_path)
                                            <a href="{{ asset('storage/' . $rev->attachment_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $rev->attachment_path) }}" class="rounded-lg max-h-48 object-cover border border-black/10 dark:border-white/10">
                                            </a>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-400 mt-1 ml-1">{{ $customOrder->user->name }} • {{ $rev->created_at->format('H:i, d M') }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                {{-- Chat Input Form --}}
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-b-2xl">
                    <form method="POST" action="{{ route('admin.web.custom-orders.revisions.store', $customOrder) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div class="flex gap-2">
                            <label class="cursor-pointer p-3 text-gray-500 hover:text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/30 rounded-xl transition-colors shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <input type="file" name="attachment_file" class="hidden" accept="image/*">
                            </label>
                            
                            <textarea name="message" rows="2" placeholder="Balas ke pelanggan..." class="flex-1 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500 resize-none"></textarea>
                            
                            <button type="submit" class="px-5 bg-violet-600 hover:bg-violet-500 text-white rounded-xl font-semibold transition-colors shrink-0">
                                Kirim
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-2 px-3">
                            <input type="checkbox" name="is_final_design" id="is_final_design" value="1" class="rounded text-violet-600 border-gray-300 focus:ring-violet-500">
                            <label for="is_final_design" class="text-xs text-gray-500 dark:text-gray-400 font-medium cursor-pointer selection:bg-transparent">Jadikan lampiran gambar ini sebagai hasil desain final (Acc)</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
