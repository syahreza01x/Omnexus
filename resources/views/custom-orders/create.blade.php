<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('custom-orders.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-2xl text-gray-500 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-purple-600 transition-all duration-200 shadow-sm focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-3xl bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 dark:from-purple-400 dark:via-violet-400 dark:to-indigo-400 bg-clip-text text-transparent leading-tight tracking-tight">
                    {{ __('Buat Custom Order') }}
                </h2>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 font-medium">Lengkapi spesifikasi pakaian custom atau merchandise Anda di bawah ini.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F8FAFC]/60 dark:bg-gray-950/20 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ─── 1. HEADER STEP PROGRESS ─── --}}
            <div class="mb-10 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-gray-100/80 dark:border-gray-800/80">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 relative">
                    {{-- Progress line --}}
                    <div class="absolute top-[22px] left-[22px] right-[22px] h-0.5 hidden md:block z-0">
                        <div class="w-full h-full bg-gray-100 dark:bg-gray-800 rounded-full"></div>
                        <div id="step-progress-line" class="absolute top-0 left-0 h-full bg-gradient-to-r from-purple-500 to-indigo-500 transition-all duration-500 shadow-md shadow-purple-500/25 rounded-full" style="width: 0%;"></div>
                    </div>

                    {{-- Step 1 --}}
                    <button type="button" onclick="goToStep(1)" class="step-btn flex flex-col items-center text-center z-10 focus:outline-none group" data-step="1">
                        <div class="step-num w-11 h-11 rounded-2xl flex items-center justify-center bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20 border-4 border-white dark:border-gray-900 transition-all duration-300 transform group-hover:scale-105">1</div>
                        <span class="text-xs font-bold text-gray-900 dark:text-white mt-3 group-hover:text-purple-600 transition">Informasi Pemesan</span>
                    </button>

                    {{-- Step 2 --}}
                    <button type="button" onclick="goToStep(2)" class="step-btn flex flex-col items-center text-center z-10 focus:outline-none group" data-step="2">
                        <div class="step-num w-11 h-11 rounded-2xl flex items-center justify-center bg-gray-50 dark:bg-gray-800 text-gray-400 font-bold border-4 border-white dark:border-gray-900 transition-all duration-300 transform group-hover:scale-105">2</div>
                        <span class="text-xs font-medium text-gray-400 mt-3 group-hover:text-purple-600 transition">Spesifikasi Produk</span>
                    </button>

                    {{-- Step 3 --}}
                    <button type="button" onclick="goToStep(3)" class="step-btn flex flex-col items-center text-center z-10 focus:outline-none group" data-step="3">
                        <div class="step-num w-11 h-11 rounded-2xl flex items-center justify-center bg-gray-50 dark:bg-gray-800 text-gray-400 font-bold border-4 border-white dark:border-gray-900 transition-all duration-300 transform group-hover:scale-105">3</div>
                        <span class="text-xs font-medium text-gray-400 mt-3 group-hover:text-purple-600 transition">Upload Desain</span>
                    </button>

                    {{-- Step 4 --}}
                    <button type="button" onclick="goToStep(4)" class="step-btn flex flex-col items-center text-center z-10 focus:outline-none group" data-step="4">
                        <div class="step-num w-11 h-11 rounded-2xl flex items-center justify-center bg-gray-50 dark:bg-gray-800 text-gray-400 font-bold border-4 border-white dark:border-gray-900 transition-all duration-300 transform group-hover:scale-105">4</div>
                        <span class="text-xs font-medium text-gray-400 mt-3 group-hover:text-purple-600 transition">Konfirmasi</span>
                    </button>
                </div>
            </div>

            <form id="custom-order-form" action="{{ route('custom-orders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    {{-- ─── 2. FORM UTAMA (LEFT SIDE) ─── --}}
                    <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-shadow duration-300 p-6 md:p-8 space-y-8">
                        
                        {{-- STEP 1: INFORMASI PEMESAN --}}
                        <div id="form-step-1" class="form-step-panel space-y-8">
                            <div class="border-b border-gray-100 dark:border-gray-800 pb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">1. Informasi Pemesan</h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Lengkapi info kontak Anda dan pilih kategori produk custom.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nama Pemesan --}}
                                <div>
                                    <label for="customer_name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nama Pemesan <span class="text-red-500">*</span></label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" placeholder="Nama Lengkap Pemesan" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200 @error('customer_name') border-red-500 @enderror">
                                    @error('customer_name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Nomor WhatsApp --}}
                                <div>
                                    <label for="whatsapp_number" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                    <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}" placeholder="Contoh: 081234567890" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200 @error('whatsapp_number') border-red-500 @enderror">
                                    @error('whatsapp_number')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Pilih Kategori (Jenis Pesanan) --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Pilih Jenis Pesanan <span class="text-red-500">*</span></label>
                                <input type="hidden" name="category" id="category_input" value="{{ old('category') }}">
                                @error('category')
                                    <p class="text-xs text-red-500 mt-1 mb-2">{{ $message }}</p>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Card Konveksi --}}
                                    <div id="category_card_konveksi" onclick="selectCategory('konveksi')" class="cursor-pointer border border-gray-150 dark:border-gray-800 rounded-3xl p-5 hover:border-purple-400 dark:hover:border-purple-900 transition-all duration-300 relative group flex gap-4 shadow-sm hover:shadow">
                                        <div class="w-12 h-12 rounded-2xl bg-purple-50/80 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200 shadow-sm">
                                            {{-- T-Shirt Icon --}}
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 3l-5 4 2 2 2-1v12a1 1 0 001 1h8a1 1 0 001-1V8l2 1 2-2-5-4-1.5 2h-5L8 3z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-sm text-gray-900 dark:text-white transition-colors duration-200 group-hover:text-purple-600">Konveksi</h4>
                                            <p class="text-xs text-gray-450 dark:text-gray-500 mt-1 leading-relaxed">Custom pakaian seperti Baju PDH, Jersey, Jaket, Kemeja, Rompi, Topi, dll.</p>
                                        </div>
                                        <div class="checkmark-circle absolute top-4 right-4 w-5 h-5 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex items-center justify-center opacity-0 transition-all duration-300 shadow shadow-purple-500/25">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>

                                    {{-- Card Merchandise --}}
                                    <div id="category_card_merchandise" onclick="selectCategory('merchandise')" class="cursor-pointer border border-gray-150 dark:border-gray-800 rounded-3xl p-5 hover:border-purple-400 dark:hover:border-purple-900 transition-all duration-300 relative group flex gap-4 shadow-sm hover:shadow">
                                        <div class="w-12 h-12 rounded-2xl bg-purple-50/80 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200 shadow-sm">
                                            {{-- Merchandise/Bag Icon --}}
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-sm text-gray-900 dark:text-white transition-colors duration-200 group-hover:text-purple-600">Merchandise</h4>
                                            <p class="text-xs text-gray-455 dark:text-gray-500 mt-1 leading-relaxed">Produk merchandise seperti Tumbler, Lanyard, ID Card, Pin, Sticker, Banner, Spanduk, dll.</p>
                                        </div>
                                        <div class="checkmark-circle absolute top-4 right-4 w-5 h-5 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex items-center justify-center opacity-0 transition-all duration-300 shadow shadow-purple-500/25">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Pilih Produk (Grid Card bergambar) --}}
                            <div id="product_selection_container" class="hidden space-y-4">
                                <label class="block text-xs font-bold text-gray-550 dark:text-gray-400 uppercase tracking-wider mb-1">Pilih Produk <span class="text-red-500">*</span></label>
                                <input type="hidden" name="product" id="product_input" value="{{ old('product') }}">
                                @error('product')
                                    <p class="text-xs text-red-500 mt-1 mb-2">{{ $message }}</p>
                                @enderror

                                {{-- Konveksi Products --}}
                                <div id="product_grid_konveksi" class="hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 animate-fadeIn">
                                    @php
                                        $konveksiProducts = [
                                            ['val' => 'baju PDH', 'label' => 'Baju PDH', 'icon' => '👔'],
                                            ['val' => 'jersey', 'label' => 'Jersey', 'icon' => '👕'],
                                            ['val' => 'Jaket', 'label' => 'Jaket', 'icon' => '🧥'],
                                            ['val' => 'Kemeja', 'label' => 'Kemeja', 'icon' => '👔'],
                                            ['val' => 'Rompi', 'label' => 'Rompi', 'icon' => '🦺'],
                                            ['val' => 'Topi', 'label' => 'Topi', 'icon' => '🧢'],
                                            ['val' => 'lainnya', 'label' => 'Lainnya', 'icon' => '➕']
                                        ];
                                    @endphp
                                    @foreach ($konveksiProducts as $prod)
                                        <div onclick="selectProduct('{{ $prod['val'] }}')" class="product-item-card cursor-pointer border border-gray-150 dark:border-gray-800 rounded-2xl p-4 text-center hover:border-purple-400 hover:shadow-sm dark:bg-gray-900/50 transition-all duration-200 flex flex-col items-center justify-center gap-2 aspect-[4/3] group" data-product="{{ $prod['val'] }}">
                                            <span class="text-3xl filter drop-shadow group-hover:scale-110 transition-transform duration-200">{{ $prod['icon'] }}</span>
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $prod['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Merchandise Products --}}
                                <div id="product_grid_merchandise" class="hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 animate-fadeIn">
                                    @php
                                        $merchandiseProducts = [
                                            ['val' => 'Tumbler', 'label' => 'Tumbler', 'icon' => '🥤'],
                                            ['val' => 'Lanyard', 'label' => 'Lanyard', 'icon' => '🎗️'],
                                            ['val' => 'ID Card', 'label' => 'ID Card', 'icon' => '🪪'],
                                            ['val' => 'Pin', 'label' => 'Pin', 'icon' => '🔘'],
                                            ['val' => 'Sticker', 'label' => 'Sticker', 'icon' => '🏷️'],
                                            ['val' => 'Banner', 'label' => 'Banner', 'icon' => '🚩'],
                                            ['val' => 'Spanduk', 'label' => 'Spanduk', 'icon' => '🏳️'],
                                            ['val' => 'lainnya', 'label' => 'Lainnya', 'icon' => '➕']
                                        ];
                                    @endphp
                                    @foreach ($merchandiseProducts as $prod)
                                        <div onclick="selectProduct('{{ $prod['val'] }}')" class="product-item-card cursor-pointer border border-gray-155 dark:border-gray-800 rounded-2xl p-4 text-center hover:border-purple-400 hover:shadow-sm dark:bg-gray-900/50 transition-all duration-200 flex flex-col items-center justify-center gap-2 aspect-[4/3] group" data-product="{{ $prod['val'] }}">
                                            <span class="text-3xl filter drop-shadow group-hover:scale-110 transition-transform duration-200">{{ $prod['icon'] }}</span>
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $prod['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Custom Product Name Input --}}
                                <div id="other_product_wrapper" class="hidden mt-4 animate-slideDown">
                                    <label for="other_product_name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nama Produk Custom <span class="text-red-500">*</span></label>
                                    <input type="text" name="other_product_name" id="other_product_name" value="{{ old('other_product_name') }}" placeholder="Tuliskan nama produk custom (misal: Plakat, Payung, dll.)" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-250 @error('other_product_name') border-red-500 @enderror">
                                    @error('other_product_name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-gray-800">
                                <button type="button" onclick="nextStep()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-purple-500/25 focus:outline-none">
                                    Lanjut ke Spesifikasi
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 2: SPESIFIKASI PRODUK --}}
                        <div id="form-step-2" class="form-step-panel hidden space-y-8">
                            <div class="border-b border-gray-100 dark:border-gray-800 pb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">2. Spesifikasi Produk</h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tentukan rincian jumlah pesanan, warna, ukuran, bahan, dan deadline.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Jumlah --}}
                                <div>
                                    <label for="quantity" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Jumlah Pesanan <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1" placeholder="Masukkan jumlah" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white pl-4 pr-16 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200 @error('quantity') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-extrabold text-gray-400 uppercase tracking-wider">
                                            Pcs
                                        </div>
                                    </div>
                                    @error('quantity')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Warna --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Pilih Warna <span class="text-red-500">*</span></label>
                                    <input type="hidden" name="color" id="color_input" value="{{ old('color') }}">
                                    @error('color')
                                        <p class="text-xs text-red-500 mt-1 mb-2">{{ $message }}</p>
                                    @enderror

                                    <div class="flex flex-wrap items-center gap-3">
                                        {{-- Circle Colors --}}
                                        <button type="button" onclick="selectColor('Hitam')" class="color-dot w-9 h-9 rounded-full border border-gray-300/40 relative focus:outline-none transition-all duration-200 hover:scale-105 shadow-sm" style="background-color: #1e293b;" title="Hitam"></button>
                                        <button type="button" onclick="selectColor('Putih')" class="color-dot w-9 h-9 rounded-full border border-gray-300/60 relative focus:outline-none transition-all duration-200 hover:scale-105 shadow-sm" style="background-color: #ffffff;" title="Putih"></button>
                                        <button type="button" onclick="selectColor('Merah')" class="color-dot w-9 h-9 rounded-full border border-gray-300/40 relative focus:outline-none transition-all duration-200 hover:scale-105 shadow-sm" style="background-color: #ef4444;" title="Merah"></button>
                                        <button type="button" onclick="selectColor('Biru')" class="color-dot w-9 h-9 rounded-full border border-gray-300/40 relative focus:outline-none transition-all duration-200 hover:scale-105 shadow-sm" style="background-color: #3b82f6;" title="Biru"></button>
                                        <button type="button" onclick="selectColor('Hijau')" class="color-dot w-9 h-9 rounded-full border border-gray-300/40 relative focus:outline-none transition-all duration-200 hover:scale-105 shadow-sm" style="background-color: #10b981;" title="Hijau"></button>
                                        <button type="button" onclick="selectColor('Kuning')" class="color-dot w-9 h-9 rounded-full border border-gray-300/40 relative focus:outline-none transition-all duration-200 hover:scale-105 shadow-sm" style="background-color: #eab308;" title="Kuning"></button>

                                        {{-- Custom Color Trigger --}}
                                        <button type="button" id="custom_color_btn" onclick="toggleCustomColor()" class="px-3.5 py-2 border border-dashed border-gray-300 dark:border-gray-700 hover:border-purple-500 rounded-2xl text-[10px] font-bold text-gray-500 dark:text-gray-400 hover:text-purple-600 transition-colors">
                                            + Warna Custom
                                        </button>
                                    </div>

                                    {{-- Custom Color Text Input --}}
                                    <div id="custom_color_wrapper" class="hidden mt-3 animate-slideDown">
                                        <input type="text" id="custom_color_name" placeholder="Tuliskan nama warna (misal: Navy, Maroon, dll.)" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200">
                                    </div>
                                </div>
                            </div>

                            {{-- Ukuran --}}
                            <div id="size_section_container" class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Pilih Ukuran <span class="text-red-500">*</span></label>
                                <input type="hidden" name="size" id="size_input" value="{{ old('size') }}">
                                @error('size')
                                    <p class="text-xs text-red-500 mt-1 mb-2">{{ $message }}</p>
                                @enderror

                                {{-- Konveksi Sizes (S, M, L, XL, XXL, Custom) --}}
                                <div id="size_options_konveksi" class="hidden flex flex-wrap gap-2.5">
                                    @foreach(['S', 'M', 'L', 'XL', 'XXL', 'Custom Size'] as $sz)
                                        <button type="button" onclick="selectSize('{{ $sz }}')" class="size-item-btn px-5 py-2.5 border border-gray-200 dark:border-gray-800 rounded-2xl text-xs font-bold hover:border-purple-400 dark:bg-gray-900/50 hover:bg-gray-50/50 dark:hover:bg-gray-800 transition duration-150" data-size="{{ $sz }}">{{ $sz }}</button>
                                    @endforeach
                                </div>

                                {{-- Merchandise Sizes --}}
                                <div id="size_options_merchandise" class="hidden flex flex-wrap gap-2.5">
                                    {{-- Will be dynamically populated via JS based on selected product --}}
                                </div>

                                {{-- Custom Size Input --}}
                                <div id="custom_size_wrapper" class="hidden mt-3 animate-slideDown">
                                    <input type="text" id="custom_size_name" placeholder="Tuliskan ukuran custom Anda (misal: Panjang x Lebar, dll.)" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Jenis Bahan --}}
                                <div>
                                    <label for="material_select" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Jenis Bahan <span class="text-red-500">*</span></label>
                                    <select id="material_select" onchange="handleMaterialChange(this.value)" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200">
                                        {{-- Populated via JS based on category --}}
                                    </select>
                                    <input type="hidden" name="material_type" id="material_input_hidden" value="{{ old('material_type') }}">
                                    @error('material_type')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror

                                    {{-- Custom Material Input --}}
                                    <div id="custom_material_wrapper" class="hidden mt-3 animate-slideDown">
                                        <input type="text" id="custom_material_name" placeholder="Tuliskan nama bahan kustom Anda" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200">
                                    </div>
                                </div>

                                {{-- Deadline --}}
                                <div>
                                    <label for="deadline" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deadline Pengerjaan <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200 @error('deadline') border-red-500 @enderror">
                                    </div>
                                    @error('deadline')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Teknik Produksi --}}
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Teknik Produksi <span class="text-red-500">*</span></label>
                                <input type="hidden" name="production_technique" id="production_technique_input" value="{{ old('production_technique') }}">
                                @error('production_technique')
                                    <p class="text-xs text-red-500 mt-1 mb-2">{{ $message }}</p>
                                @enderror

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    @foreach(['Sablon', 'Bordir', 'Printing', 'DTF', 'UV Print', 'Laser Engraving', 'Lainnya'] as $tech)
                                        <button type="button" onclick="selectTechnique('{{ $tech }}')" class="tech-item-btn px-4 py-2.5 border border-gray-200 dark:border-gray-800 rounded-2xl text-xs font-bold hover:border-purple-400 dark:bg-gray-900/50 hover:bg-gray-50/50 dark:hover:bg-gray-800 transition duration-150" data-tech="{{ $tech }}">{{ $tech }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-between pt-6 border-t border-gray-100 dark:border-gray-800">
                                <button type="button" onclick="goToStep(1)" class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl text-xs uppercase tracking-wider hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-200 focus:outline-none">
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-purple-500/25 focus:outline-none">
                                    Lanjut ke Desain
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 3: UPLOAD DESAIN --}}
                        <div id="form-step-3" class="form-step-panel hidden space-y-8">
                            <div class="border-b border-gray-100 dark:border-gray-800 pb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">3. Upload File Desain</h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Unggah berkas konsep desain Anda dan tulis catatan pendukung.</p>
                            </div>

                            {{-- Drag & Drop Area --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Upload File Desain <span class="text-red-500">*</span></label>
                                
                                <div id="drop_zone" onclick="triggerFileInput()" class="cursor-pointer border-2 border-dashed border-gray-200 dark:border-gray-800 hover:border-purple-400 rounded-3xl p-8 text-center bg-gray-50/20 hover:bg-purple-50/5 dark:hover:bg-purple-950/5 transition-all duration-300 flex flex-col items-center justify-center gap-4 group">
                                    <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-200">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-purple-600 transition-colors">Drag & Drop berkas desain di sini</p>
                                        <p class="text-xs text-gray-450 dark:text-gray-500 mt-1 font-medium">Atau klik untuk menjelajahi berkas komputer Anda</p>
                                    </div>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-widest font-extrabold bg-gray-150/40 dark:bg-gray-850 px-3 py-1 rounded-full border border-gray-200 dark:border-gray-700">Format: JPG, JPEG, PNG, PDF, AI, CDR (Maksimal 10MB)</p>
                                </div>
                                
                                <input type="file" name="design_file" id="design_file" onchange="handleFileSelect(this)" class="hidden">
                                @error('design_file')
                                    <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                                @enderror

                                {{-- File Preview Info --}}
                                <div id="file_preview_container" class="hidden mt-4 p-4 rounded-2xl border border-emerald-100 bg-emerald-50/10 dark:border-emerald-950/20 dark:bg-emerald-950/10 flex items-center justify-between gap-3 animate-slideDown shadow-inner">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p id="file_name_display" class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate"></p>
                                            <p id="file_size_display" class="text-[10px] text-gray-400 mt-0.5 font-mono"></p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeFile()" class="p-2 text-gray-400 hover:text-red-500 rounded-xl hover:bg-red-50/20 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Catatan Tambahan --}}
                            <div>
                                <label for="notes" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Catatan Tambahan</label>
                                <textarea name="notes" id="notes" rows="5" oninput="updateSummaryNotes(this.value)" placeholder="Tuliskan spesifikasi tambahan seperti posisi logo, ukuran desain, warna khusus, finishing, custom bahan, atau permintaan lainnya." class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 focus:outline-none transition-all duration-200 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-between pt-6 border-t border-gray-100 dark:border-gray-800">
                                <button type="button" onclick="goToStep(2)" class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl text-xs uppercase tracking-wider hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-200 focus:outline-none">
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-purple-500/25 focus:outline-none">
                                    Lanjut ke Tinjauan
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 4: KONFIRMASI --}}
                        <div id="form-step-4" class="form-step-panel hidden space-y-8">
                            <div class="border-b border-gray-100 dark:border-gray-800 pb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">4. Tinjau & Konfirmasi</h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Periksa kembali rincian data spesifikasi pesanan Anda sebelum dikirim.</p>
                            </div>

                            {{-- Review Summary Panel --}}
                            <div class="rounded-3xl border border-gray-100 dark:border-gray-800 p-6 bg-[#F8FAFC]/50 dark:bg-gray-950/20 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm font-semibold">
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Nama Pemesan</span>
                                        <span id="review_customer_name" class="font-bold text-gray-900 dark:text-white mt-1 block">-</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Nomor WhatsApp</span>
                                        <span id="review_whatsapp" class="font-semibold text-gray-900 dark:text-white mt-1 block font-mono">-</span>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-850 pt-3">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Kategori</span>
                                        <span id="review_category" class="font-extrabold text-purple-600 dark:text-purple-400 mt-1 block">-</span>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-850 pt-3">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Produk</span>
                                        <span id="review_product" class="font-semibold text-gray-900 dark:text-white mt-1 block">-</span>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-850 pt-3">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Jumlah</span>
                                        <span id="review_quantity" class="font-semibold text-gray-900 dark:text-white mt-1 block">-</span>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-850 pt-3">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Warna</span>
                                        <span id="review_color" class="font-semibold text-gray-900 dark:text-white mt-1 block">-</span>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-850 pt-3">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Ukuran</span>
                                        <span id="review_size" class="font-semibold text-gray-900 dark:text-white mt-1 block">-</span>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-850 pt-3">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Bahan</span>
                                        <span id="review_material" class="font-semibold text-gray-900 dark:text-white mt-1 block">-</span>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-850 pt-3">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Teknik Produksi</span>
                                        <span id="review_technique" class="font-semibold text-gray-900 dark:text-white mt-1 block">-</span>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-850 pt-3">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Deadline</span>
                                        <span id="review_deadline" class="font-bold text-gray-950 dark:text-white mt-1 block">-</span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-3">
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Berkas Desain</span>
                                        <span id="review_file" class="font-semibold text-emerald-600 dark:text-emerald-400 mt-1 block">Tidak ada berkas.</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Catatan Tambahan</span>
                                        <p id="review_notes" class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mt-1 font-medium">-</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Confirm Term Checkbox --}}
                            <div class="flex items-start gap-3 p-4 rounded-2xl border border-purple-100 bg-purple-50/10 dark:border-purple-950/20 dark:bg-purple-950/10">
                                <input type="checkbox" id="agree_terms" required class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 mt-0.5">
                                <label for="agree_terms" class="text-xs text-purple-950 dark:text-purple-400 leading-relaxed font-semibold">
                                    Saya menyatakan bahwa seluruh spesifikasi pesanan di atas sudah benar. Saya paham bahwa harga akhir akan disesuaikan oleh admin setelah peninjauan berkas desain.
                                </label>
                            </div>

                            <div class="flex justify-between pt-6 border-t border-gray-100 dark:border-gray-800">
                                <button type="button" onclick="goToStep(3)" class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl text-xs uppercase tracking-wider hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-200 focus:outline-none">
                                    Kembali
                                </button>
                                <div class="flex gap-2">
                                    <button type="button" onclick="submitDraft()" class="px-5 py-2.5 border border-purple-300 text-purple-700 hover:bg-purple-50 dark:hover:bg-purple-900/10 font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-200">
                                        Simpan Draft
                                    </button>
                                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition duration-150 hover:-translate-y-0.5 shadow-lg shadow-purple-500/25">
                                        Kirim Custom Order
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ─── 3. RINGKASAN PESANAN (RIGHT SIDEBAR) ─── --}}
                    <div class="lg:sticky lg:top-8 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-shadow duration-300 p-6 space-y-6">
                        <div>
                            <h3 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2.5">
                                Ringkasan Pesanan
                            </h3>
                            <p class="text-[10px] text-gray-400 mt-1 font-medium">Rincian pesanan terupdate secara waktu nyata.</p>
                        </div>

                        {{-- Category Visual Box --}}
                        <div id="summary_visual_box" class="h-32 rounded-2xl bg-purple-50/50 dark:bg-purple-950/10 border border-purple-100/50 dark:border-purple-950/20 flex flex-col items-center justify-center gap-2 text-purple-600 dark:text-purple-400 shadow-inner">
                            <span id="summary_visual_icon" class="text-4xl">📦</span>
                            <span id="summary_visual_text" class="text-[10px] font-bold uppercase tracking-wider">Pilih Kategori</span>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-800 text-xs font-semibold space-y-3.5">
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-gray-400">Kategori</span>
                                <span id="summary_category" class="text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-gray-400">Produk</span>
                                <span id="summary_product" class="text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-gray-400">Jumlah</span>
                                <span id="summary_quantity" class="text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-gray-400">Warna</span>
                                <span id="summary_color" class="text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-gray-400">Ukuran</span>
                                <span id="summary_size" class="text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-gray-400">Bahan</span>
                                <span id="summary_material" class="text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-gray-400">Teknik</span>
                                <span id="summary_technique" class="text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-gray-400">Deadline</span>
                                <span id="summary_deadline" class="text-gray-900 dark:text-white">-</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-3.5 text-xs font-semibold">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Estimasi Produksi</span>
                                <span class="text-gray-900 dark:text-white">7 - 10 Hari Kerja</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Estimasi Harga</span>
                                <span class="text-purple-600 dark:text-purple-400 font-bold">Dihitung Admin</span>
                            </div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-relaxed text-center font-medium bg-[#F8FAFC] dark:bg-gray-950/20 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                                ℹ️ Harga akhir akan dikonfirmasi oleh admin setelah desain dan spesifikasi direview.
                            </p>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- Interactive wizard JavaScript --}}
    <script>
        // Store selected values
        let selectedCategory = '';
        let selectedProduct = '';
        let selectedColor = '';
        let selectedSize = '';
        let selectedTechnique = '';
        let currentStep = 1;

        const sizeOptionsMap = {
            Banner: ['60 × 160 cm', '80 × 180 cm', '100 × 200 cm', 'Custom'],
            Spanduk: ['1 × 3 m', '1 × 4 m', '1 × 5 m', 'Custom'],
            Tumbler: ['350 ml', '500 ml', '750 ml', 'Custom'],
            Lanyard: ['1.5 cm', '2 cm', '2.5 cm', 'Custom'],
            'ID Card': ['Standar', 'Custom'],
            Sticker: ['Bulat', 'Kotak', 'Oval', 'Custom'],
            Pin: ['44 mm', '58 mm', 'Custom']
        };

        const materialOptionsMap = {
            konveksi: [
                'Cotton Combed 24s',
                'Cotton Combed 30s',
                'Drill',
                'Oxford',
                'Fleece',
                'Lacoste',
                'Lainnya (Custom Bahan)'
            ],
            merchandise: [
                'Stainless Steel',
                'Plastik',
                'Vinyl',
                'Acrylic',
                'Flexi Banner',
                'Albatros',
                'Kain Satin',
                'PVC',
                'Lainnya (Custom Bahan)'
            ]
        };

        const categoryIcons = {
            konveksi: '👕',
            merchandise: '🎁'
        };

        const productIcons = {
            'baju PDH': '👔',
            jersey: '👕',
            Jaket: '🧥',
            Kemeja: '👔',
            Rompi: '🦺',
            Topi: '🧢',
            Tumbler: '🥤',
            Lanyard: '🎗️',
            'ID Card': '🪪',
            Pin: '🔘',
            Sticker: '🏷️',
            Banner: '🚩',
            Spanduk: '🏳️',
            lainnya: '➕'
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Restore Old Values if they exist (Validation errors fallback)
            const oldCategory = "{{ old('category') }}";
            const oldProduct = "{{ old('product') }}";
            const oldColor = "{{ old('color') }}";
            const oldSize = "{{ old('size') }}";
            const oldTechnique = "{{ old('production_technique') }}";
            const oldMaterial = "{{ old('material_type') }}";
            const oldNotes = "{{ old('notes') }}";

            // Bind live inputs to review step
            document.getElementById('customer_name').addEventListener('input', updateReviewInfo);
            document.getElementById('whatsapp_number').addEventListener('input', updateReviewInfo);
            document.getElementById('quantity').addEventListener('input', function(e) {
                const val = e.target.value;
                document.getElementById('summary_quantity').innerText = val ? val + ' Pcs' : '-';
                document.getElementById('review_quantity').innerText = val ? val + ' Pcs' : '-';
            });
            document.getElementById('deadline').addEventListener('change', function(e) {
                const val = e.target.value;
                if(val) {
                    const formatted = new Date(val).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    document.getElementById('summary_deadline').innerText = formatted;
                    document.getElementById('review_deadline').innerText = formatted;
                } else {
                    document.getElementById('summary_deadline').innerText = '-';
                    document.getElementById('review_deadline').innerText = '-';
                }
            });

            // Handle material custom inputs
            document.getElementById('custom_material_name').addEventListener('input', function(e) {
                const val = e.target.value;
                document.getElementById('material_input_hidden').value = val;
                document.getElementById('summary_material').innerText = val || 'Custom';
                document.getElementById('review_material').innerText = val || 'Custom';
            });

            // Handle size custom inputs
            document.getElementById('custom_size_name').addEventListener('input', function(e) {
                const val = e.target.value;
                document.getElementById('size_input').value = val;
                document.getElementById('summary_size').innerText = val || 'Custom';
                document.getElementById('review_size').innerText = val || 'Custom';
            });

            // Handle color custom inputs
            document.getElementById('custom_color_name').addEventListener('input', function(e) {
                const val = e.target.value;
                document.getElementById('color_input').value = val;
                document.getElementById('summary_color').innerText = val || 'Custom';
                document.getElementById('review_color').innerText = val || 'Custom';
            });

            // If old inputs exist, hydrate them
            if (oldCategory) selectCategory(oldCategory);
            if (oldProduct) selectProduct(oldProduct);
            if (oldColor) {
                if (['Hitam', 'Putih', 'Merah', 'Biru', 'Hijau', 'Kuning'].includes(oldColor)) {
                    selectColor(oldColor);
                } else {
                    toggleCustomColor();
                    document.getElementById('custom_color_name').value = oldColor;
                    selectColor(oldColor);
                }
            }
            if (oldSize) {
                if (selectedCategory === 'konveksi' && ['S', 'M', 'L', 'XL', 'XXL'].includes(oldSize)) {
                    selectSize(oldSize);
                } else {
                    // Check if sizes are in mapped lists or custom
                    selectSize(oldSize);
                }
            }
            if (oldTechnique) selectTechnique(oldTechnique);
            if (oldMaterial) {
                handleOldMaterial(oldMaterial);
            }
            if (oldNotes) updateSummaryNotes(oldNotes);

            // Drag & Drop event bindings
            const dropZone = document.getElementById('drop_zone');
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });
            function preventDefaults (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('border-purple-500', 'bg-purple-50/10'), false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-purple-500', 'bg-purple-50/10'), false);
            });
            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if(files.length > 0) {
                    const fileInput = document.getElementById('design_file');
                    fileInput.files = files;
                    handleFileSelect(fileInput);
                }
            }

            // Route to correct step on validation errors
            @if ($errors->any())
                @if ($errors->has('customer_name') || $errors->has('whatsapp_number') || $errors->has('category') || $errors->has('product') || $errors->has('other_product_name'))
                    goToStep(1);
                @elseif ($errors->has('quantity') || $errors->has('color') || $errors->has('size') || $errors->has('material_type') || $errors->has('production_technique') || $errors->has('deadline'))
                    goToStep(2);
                @else
                    goToStep(3);
                @endif
            @else
                goToStep(1);
            @endif

            updateReviewInfo();
        });

        function selectCategory(category) {
            selectedCategory = category;
            document.getElementById('category_input').value = category;

            // Manage CSS states for category cards
            const cardKonveksi = document.getElementById('category_card_konveksi');
            const cardMerchandise = document.getElementById('category_card_merchandise');

            if (category === 'konveksi') {
                cardKonveksi.className = "cursor-pointer border-2 border-purple-600 rounded-3xl p-5 bg-purple-50/30 dark:bg-purple-950/10 transition duration-200 relative group flex gap-4";
                cardKonveksi.querySelector('.checkmark-circle').classList.remove('opacity-0');
                cardKonveksi.querySelector('.checkmark-circle').classList.add('opacity-100');

                cardMerchandise.className = "cursor-pointer border border-gray-200 dark:border-gray-800 rounded-3xl p-5 hover:border-purple-300 dark:hover:border-purple-900 transition duration-200 relative group flex gap-4";
                cardMerchandise.querySelector('.checkmark-circle').classList.add('opacity-0');
                cardMerchandise.querySelector('.checkmark-circle').classList.remove('opacity-100');

                // Display appropriate product lists
                document.getElementById('product_selection_container').classList.remove('hidden');
                document.getElementById('product_grid_konveksi').classList.remove('hidden');
                document.getElementById('product_grid_merchandise').classList.add('hidden');
            } else {
                cardMerchandise.className = "cursor-pointer border-2 border-purple-600 rounded-3xl p-5 bg-purple-50/30 dark:bg-purple-950/10 transition duration-200 relative group flex gap-4";
                cardMerchandise.querySelector('.checkmark-circle').classList.remove('opacity-0');
                cardMerchandise.querySelector('.checkmark-circle').classList.add('opacity-100');

                cardKonveksi.className = "cursor-pointer border border-gray-200 dark:border-gray-800 rounded-3xl p-5 hover:border-purple-300 dark:hover:border-purple-900 transition duration-200 relative group flex gap-4";
                cardKonveksi.querySelector('.checkmark-circle').classList.add('opacity-0');
                cardKonveksi.querySelector('.checkmark-circle').classList.remove('opacity-100');

                // Display appropriate product lists
                document.getElementById('product_selection_container').classList.remove('hidden');
                document.getElementById('product_grid_merchandise').classList.remove('hidden');
                document.getElementById('product_grid_konveksi').classList.add('hidden');
            }

            // Reset selected product
            selectProduct('');
            populateMaterials(category);
            updateSummaryVisual();
            updateReviewInfo();
        }

        function selectProduct(productVal) {
            selectedProduct = productVal;
            document.getElementById('product_input').value = productVal;

            // Highlight selected product card
            document.querySelectorAll('.product-item-card').forEach(card => {
                if (card.getAttribute('data-product') === productVal) {
                    card.className = "product-item-card cursor-pointer border-2 border-purple-600 rounded-2xl p-4 text-center bg-purple-50/10 dark:bg-purple-950/10 transition duration-150 flex flex-col items-center justify-center gap-2";
                } else {
                    card.className = "product-item-card cursor-pointer border border-gray-200 dark:border-gray-800 rounded-2xl p-4 text-center hover:border-purple-300 transition duration-150 flex flex-col items-center justify-center gap-2";
                }
            });

            // Toggle custom product text box
            const otherWrapper = document.getElementById('other_product_wrapper');
            const otherInput = document.getElementById('other_product_name');
            if (productVal === 'lainnya') {
                otherWrapper.classList.remove('hidden');
                otherInput.required = true;
                otherInput.addEventListener('input', function() {
                    document.getElementById('summary_product').innerText = this.value || 'Custom';
                    document.getElementById('review_product').innerText = this.value || 'Custom';
                });
            } else {
                otherWrapper.classList.add('hidden');
                otherInput.required = false;
                otherInput.value = '';
            }

            // Render and show appropriate size filters
            populateSizes(productVal);
            updateSummaryVisual();
            updateReviewInfo();
        }

        // Color circles selector function
        function selectColor(colorName) {
            selectedColor = colorName;
            document.getElementById('color_input').value = colorName;

            document.querySelectorAll('.color-dot').forEach(btn => {
                if (btn.getAttribute('title') === colorName) {
                    btn.classList.add('ring-4', 'ring-purple-500', 'ring-offset-2', 'dark:ring-offset-gray-900');
                } else {
                    btn.classList.remove('ring-4', 'ring-purple-500', 'ring-offset-2', 'dark:ring-offset-gray-900');
                }
            });

            document.getElementById('summary_color').innerText = colorName || '-';
            document.getElementById('review_color').innerText = colorName || '-';
        }

        function toggleCustomColor() {
            const wrapper = document.getElementById('custom_color_wrapper');
            if (wrapper.classList.contains('hidden')) {
                wrapper.classList.remove('hidden');
                document.getElementById('custom_color_name').required = true;
                selectColor('');
            } else {
                wrapper.classList.add('hidden');
                document.getElementById('custom_color_name').required = false;
                document.getElementById('custom_color_name').value = '';
            }
        }

        function populateSizes(product) {
            const sizeSec = document.getElementById('size_section_container');
            const konveksiBox = document.getElementById('size_options_konveksi');
            const merchandiseBox = document.getElementById('size_options_merchandise');
            const customWrapper = document.getElementById('custom_size_wrapper');
            const customInput = document.getElementById('custom_size_name');

            // Reset active sizes
            selectSize('');
            customWrapper.classList.add('hidden');
            customInput.value = '';

            if (!product) {
                sizeSec.classList.add('hidden');
                return;
            }

            sizeSec.classList.remove('hidden');

            if (selectedCategory === 'konveksi') {
                konveksiBox.classList.remove('hidden');
                merchandiseBox.classList.add('hidden');
            } else {
                konveksiBox.classList.add('hidden');
                merchandiseBox.classList.remove('hidden');
                merchandiseBox.innerHTML = '';

                // Get size templates for merchandise
                const sizes = sizeOptionsMap[product] || ['Custom'];
                sizes.forEach(sz => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = "size-item-btn px-5 py-2.5 border border-gray-200 dark:border-gray-800 rounded-2xl text-xs font-bold hover:border-purple-400 transition";
                    btn.textContent = sz;
                    btn.setAttribute('data-size', sz);
                    btn.onclick = () => selectSize(sz);
                    merchandiseBox.appendChild(btn);
                });
            }
        }

        function selectSize(sizeVal) {
            selectedSize = sizeVal;
            document.getElementById('size_input').value = sizeVal;

            document.querySelectorAll('.size-item-btn').forEach(btn => {
                if (btn.getAttribute('data-size') === sizeVal) {
                    btn.className = "size-item-btn px-5 py-2.5 border-2 border-purple-600 rounded-2xl text-xs font-bold bg-purple-50/10 dark:bg-purple-950/10 text-purple-700 dark:text-purple-455 transition";
                } else {
                    btn.className = "size-item-btn px-5 py-2.5 border border-gray-200 dark:border-gray-800 rounded-2xl text-xs font-bold hover:border-purple-400 transition";
                }
            });

            // Toggle custom size input box
            const wrapper = document.getElementById('custom_size_wrapper');
            if (sizeVal === 'Custom' || sizeVal === 'Custom Size') {
                wrapper.classList.remove('hidden');
                document.getElementById('custom_size_name').required = true;
            } else {
                wrapper.classList.add('hidden');
                document.getElementById('custom_size_name').required = false;
                document.getElementById('custom_size_name').value = '';
            }

            document.getElementById('summary_size').innerText = sizeVal || '-';
            document.getElementById('review_size').innerText = sizeVal || '-';
        }

        function populateMaterials(category) {
            const selectEl = document.getElementById('material_select');
            selectEl.innerHTML = '<option value="" disabled selected>Pilih Bahan</option>';

            const wrapper = document.getElementById('custom_material_wrapper');
            wrapper.classList.add('hidden');
            document.getElementById('custom_material_name').value = '';
            document.getElementById('material_input_hidden').value = '';

            if (category && materialOptionsMap[category]) {
                materialOptionsMap[category].forEach(mat => {
                    const opt = document.createElement('option');
                    opt.value = mat;
                    opt.textContent = mat;
                    selectEl.appendChild(opt);
                });
            }
        }

        function handleMaterialChange(value) {
            const wrapper = document.getElementById('custom_material_wrapper');
            const customInput = document.getElementById('custom_material_name');
            const hiddenInput = document.getElementById('material_input_hidden');

            if (value === 'Lainnya (Custom Bahan)') {
                wrapper.classList.remove('hidden');
                customInput.required = true;
                hiddenInput.value = '';
                document.getElementById('summary_material').innerText = 'Custom';
                document.getElementById('review_material').innerText = 'Custom';
            } else {
                wrapper.classList.add('hidden');
                customInput.required = false;
                customInput.value = '';
                hiddenInput.value = value;
                document.getElementById('summary_material').innerText = value || '-';
                document.getElementById('review_material').innerText = value || '-';
            }
        }

        function handleOldMaterial(oldVal) {
            const selectEl = document.getElementById('material_select');
            let isPredefined = false;
            for(let i=0; i<selectEl.options.length; i++) {
                if(selectEl.options[i].value === oldVal) {
                    selectEl.selectedIndex = i;
                    isPredefined = true;
                    break;
                }
            }

            if(!isPredefined && oldVal) {
                for(let i=0; i<selectEl.options.length; i++) {
                    if(selectEl.options[i].value === 'Lainnya (Custom Bahan)') {
                        selectEl.selectedIndex = i;
                        break;
                    }
                }
                document.getElementById('custom_material_wrapper').classList.remove('hidden');
                document.getElementById('custom_material_name').value = oldVal;
            }
            document.getElementById('material_input_hidden').value = oldVal;
            document.getElementById('summary_material').innerText = oldVal || '-';
            document.getElementById('review_material').innerText = oldVal || '-';
        }

        function selectTechnique(techName) {
            selectedTechnique = techName;
            document.getElementById('production_technique_input').value = techName;

            document.querySelectorAll('.tech-item-btn').forEach(btn => {
                if (btn.getAttribute('data-tech') === techName) {
                    btn.className = "tech-item-btn px-4 py-2.5 border-2 border-purple-600 rounded-2xl text-xs font-bold bg-purple-50/10 dark:bg-purple-950/10 text-purple-700 dark:text-purple-455 transition";
                } else {
                    btn.className = "tech-item-btn px-4 py-2.5 border border-gray-200 dark:border-gray-800 rounded-2xl text-xs font-bold hover:border-purple-400 transition";
                }
            });

            document.getElementById('summary_technique').innerText = techName || '-';
            document.getElementById('review_technique').innerText = techName || '-';
        }

        // Drag & drop file trigger
        function triggerFileInput() {
            document.getElementById('design_file').click();
        }

        function handleFileSelect(input) {
            const container = document.getElementById('file_preview_container');
            const file = input.files[0];

            if (file) {
                document.getElementById('file_name_display').innerText = file.name;
                document.getElementById('file_size_display').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                container.classList.remove('hidden');
                document.getElementById('review_file').innerText = file.name;
                document.getElementById('review_file').className = "font-semibold text-emerald-600 dark:text-emerald-400 mt-1 block";
            } else {
                container.classList.add('hidden');
                document.getElementById('review_file').innerText = "Tidak ada berkas.";
                document.getElementById('review_file').className = "font-semibold text-gray-400 dark:text-gray-500 mt-1 block";
            }
        }

        function removeFile() {
            document.getElementById('design_file').value = '';
            document.getElementById('file_preview_container').classList.add('hidden');
            document.getElementById('review_file').innerText = "Tidak ada berkas.";
            document.getElementById('review_file').className = "font-semibold text-gray-400 dark:text-gray-500 mt-1 block";
        }

        function updateSummaryNotes(val) {
            document.getElementById('review_notes').innerText = val || '-';
        }

        function updateSummaryVisual() {
            const box = document.getElementById('summary_visual_box');
            const icon = document.getElementById('summary_visual_icon');
            const txt = document.getElementById('summary_visual_text');

            if (selectedProduct && selectedProduct !== 'lainnya') {
                icon.innerText = productIcons[selectedProduct] || '📦';
                txt.innerText = selectedProduct;
            } else if (selectedCategory) {
                icon.innerText = categoryIcons[selectedCategory] || '📦';
                txt.innerText = selectedCategory;
            } else {
                icon.innerText = '📦';
                txt.innerText = 'Pilih Kategori';
            }

            const otherNameInput = document.getElementById('other_product_name').value;
            document.getElementById('summary_category').innerText = selectedCategory ? selectedCategory.toUpperCase() : '-';
            document.getElementById('summary_product').innerText = selectedProduct === 'lainnya' ? (otherNameInput || 'Custom') : (selectedProduct || '-');

            document.getElementById('review_category').innerText = selectedCategory ? selectedCategory.toUpperCase() : '-';
            document.getElementById('review_product').innerText = selectedProduct === 'lainnya' ? (otherNameInput || 'Custom') : (selectedProduct || '-');
        }

        function updateReviewInfo() {
            document.getElementById('review_customer_name').innerText = document.getElementById('customer_name').value || '-';
            document.getElementById('review_whatsapp').innerText = document.getElementById('whatsapp_number').value || '-';
        }

        // Wizard navigation helper functions
        function goToStep(step) {
            if(step > 1 && !selectedCategory) {
                return;
            }

            currentStep = step;

            document.querySelectorAll('.form-step-panel').forEach((panel, idx) => {
                if (idx + 1 === step) {
                    panel.classList.remove('hidden');
                } else {
                    panel.classList.add('hidden');
                }
            });

            document.querySelectorAll('.step-btn').forEach((btn) => {
                const sNum = parseInt(btn.getAttribute('data-step'));
                const circle = btn.querySelector('.step-num');

                if (sNum === step) {
                    circle.className = "step-num w-11 h-11 rounded-2xl flex items-center justify-center bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20 border-4 border-white dark:border-gray-900 transition-all duration-300";
                    btn.querySelector('span').className = "text-xs font-bold text-gray-900 dark:text-white mt-3";
                } else if (sNum < step) {
                    circle.className = "step-num w-11 h-11 rounded-2xl flex items-center justify-center bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-500/10 border-4 border-white dark:border-gray-900 transition-all duration-300";
                    circle.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>`;
                    btn.querySelector('span').className = "text-xs font-medium text-gray-400 mt-3";
                } else {
                    circle.className = "step-num w-11 h-11 rounded-2xl flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-400 font-bold border-4 border-white dark:border-gray-900 transition-all duration-300";
                    circle.innerText = sNum;
                    btn.querySelector('span').className = "text-xs font-medium text-gray-400 mt-3";
                }
            });

            const progressLine = document.getElementById('step-progress-line');
            if (step === 1) progressLine.style.width = '0%';
            else if (step === 2) progressLine.style.width = '33%';
            else if (step === 3) progressLine.style.width = '66%';
            else if (step === 4) progressLine.style.width = '100%';
        }

        function nextStep() {
            if (currentStep < 4) {
                goToStep(currentStep + 1);
            }
        }

        function submitDraft() {
            document.getElementById('custom-order-form').submit();
        }
    </script>
</x-app-layout>
