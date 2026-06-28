<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('custom-orders.index') }}" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:bg-gray-150 dark:hover:bg-gray-700 transition duration-150 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-3xl bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 dark:from-purple-400 dark:via-violet-400 dark:to-indigo-400 bg-clip-text text-transparent leading-tight">
                    {{ __('Buat Custom Order') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lengkapi spesifikasi pakaian custom atau merchandise Anda di bawah ini.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 dark:bg-gray-950/20">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-800 transition-all duration-300">
                <div class="p-6 md:p-8">
                    <form action="{{ route('custom-orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Pemesan --}}
                            <div>
                                <label for="customer_name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nama Pemesan <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" placeholder="Nama Lengkap Pemesan" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('customer_name') border-red-500 @enderror">
                                @error('customer_name')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nomor WhatsApp --}}
                            <div>
                                <label for="whatsapp_number" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}" placeholder="Contoh: 081234567890" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('whatsapp_number') border-red-500 @enderror">
                                @error('whatsapp_number')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kategori --}}
                            <div>
                                <label for="category" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Kategori Pesanan <span class="text-red-500">*</span></label>
                                <select name="category" id="category" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('category') border-red-500 @enderror">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="konveksi" {{ old('category') === 'konveksi' ? 'selected' : '' }}>Konveksi</option>
                                    <option value="merchandise" {{ old('category') === 'merchandise' ? 'selected' : '' }}>Merchandise</option>
                                </select>
                                @error('category')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Produk --}}
                            <div>
                                <label for="product" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Produk <span class="text-red-500">*</span></label>
                                <select name="product" id="product" disabled class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-950/50 text-gray-400 px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('product') border-red-500 @enderror">
                                    <option value="" disabled selected>Pilih kategori terlebih dahulu</option>
                                </select>
                                @error('product')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Input Nama Produk Lainnya (Hanya muncul jika memilih "Lainnya") --}}
                        <div id="other_product_wrapper" class="hidden">
                            <label for="other_product_name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nama Produk Lainnya <span class="text-red-500">*</span></label>
                            <input type="text" name="other_product_name" id="other_product_name" value="{{ old('other_product_name') }}" placeholder="Contoh: Plakat, Gantungan Kunci, Souvenir, dll." class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('other_product_name') border-red-500 @enderror">
                            @error('other_product_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Jumlah --}}
                            <div>
                                <label for="quantity" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Jumlah <span class="text-red-500">*</span></label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1" placeholder="Pcs" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('quantity') border-red-500 @enderror">
                                @error('quantity')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Warna --}}
                            <div>
                                <label for="color" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Warna <span class="text-red-500">*</span></label>
                                <input type="text" name="color" id="color" value="{{ old('color') }}" placeholder="Contoh: Navy" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('color') border-red-500 @enderror">
                                @error('color')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ukuran (Hanya untuk Konveksi) --}}
                            <div id="size_wrapper" class="hidden">
                                <label for="size" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Ukuran <span class="text-red-500">*</span></label>
                                <input type="text" name="size" id="size" value="{{ old('size') }}" placeholder="Contoh: S, M, L" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('size') border-red-500 @enderror">
                                @error('size')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Jenis Bahan --}}
                            <div>
                                <label for="material_type" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Jenis Bahan <span class="text-red-500">*</span></label>
                                <input type="text" name="material_type" id="material_type" value="{{ old('material_type') }}" placeholder="Contoh: Cotton Combed 30s" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('material_type') border-red-500 @enderror">
                                @error('material_type')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Teknik Produksi --}}
                            <div>
                                <label for="production_technique" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Teknik Produksi <span class="text-red-500">*</span></label>
                                <select name="production_technique" id="production_technique" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('production_technique') border-red-500 @enderror">
                                    <option value="" disabled selected>Pilih Teknik</option>
                                    <option value="Sablon" {{ old('production_technique') === 'Sablon' ? 'selected' : '' }}>Sablon</option>
                                    <option value="Bordir" {{ old('production_technique') === 'Bordir' ? 'selected' : '' }}>Bordir</option>
                                    <option value="Printing" {{ old('production_technique') === 'Printing' ? 'selected' : '' }}>Printing</option>
                                    <option value="DTF" {{ old('production_technique') === 'DTF' ? 'selected' : '' }}>DTF</option>
                                    <option value="UV Print" {{ old('production_technique') === 'UV Print' ? 'selected' : '' }}>UV Print</option>
                                    <option value="Lainnya" {{ old('production_technique') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('production_technique')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Deadline --}}
                            <div>
                                <label for="deadline" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deadline <span class="text-red-500">*</span></label>
                                <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('deadline') border-red-500 @enderror">
                                @error('deadline')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Upload File Desain --}}
                            <div>
                                <label for="design_file" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Upload File Desain <span class="text-red-500">*</span></label>
                                <input type="file" name="design_file" id="design_file" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 dark:file:bg-purple-900/30 dark:file:text-purple-400 hover:file:bg-purple-100 transition @error('design_file') border-red-500 @enderror">
                                <p class="text-[10px] text-gray-450 mt-1.5">Mendukung: JPG, JPEG, PNG, PDF, AI, CDR. Maksimal 10MB.</p>
                                @error('design_file')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Catatan Tambahan --}}
                        <div>
                            <label for="notes" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Catatan Tambahan</label>
                            <textarea name="notes" id="notes" rows="4" placeholder="Tuliskan spesifikasi detail tambahan di sini (rincian ukuran, posisi desain, dsb.)" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
                            <a href="{{ route('custom-orders.index') }}" class="px-5 py-2.5 border border-gray-250 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-150">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-2xl text-sm transition duration-150 shadow-lg shadow-purple-500/20">
                                Kirim Custom Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Javascript dynamic form behavior --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('category');
            const productSelect = document.getElementById('product');
            const sizeWrapper = document.getElementById('size_wrapper');
            const otherProductWrapper = document.getElementById('other_product_wrapper');
            const sizeInput = document.getElementById('size');
            const otherProductInput = document.getElementById('other_product_name');

            const productsData = {
                konveksi: [
                    { value: 'baju PDH', text: 'Baju PDH' },
                    { value: 'jersey', text: 'Jersey' },
                    { value: 'Jaket', text: 'Jaket' },
                    { value: 'Kemeja', text: 'Kemeja' },
                    { value: 'Rompi', text: 'Rompi' },
                    { value: 'Topi', text: 'Topi' },
                    { value: 'lainnya', text: 'Lainnya' }
                ],
                merchandise: [
                    { value: 'Totebag', text: 'Totebag' },
                    { value: 'Tumbler', text: 'Tumbler' },
                    { value: 'Lanyard', text: 'Lanyard' },
                    { value: 'ID Card', text: 'ID Card' },
                    { value: 'Pin', text: 'Pin' },
                    { value: 'Sticker', text: 'Sticker' },
                    { value: 'Banner', text: 'Banner' },
                    { value: 'Spanduk', text: 'Spanduk' },
                    { value: 'lainnya', text: 'Lainnya' }
                ]
            };

            function handleCategoryChange(selectedCategory, oldProductValue = '') {
                productSelect.innerHTML = '<option value="" disabled selected>Pilih Produk</option>';
                
                if (selectedCategory && productsData[selectedCategory]) {
                    productSelect.disabled = false;
                    productSelect.classList.remove('bg-gray-50/50', 'dark:bg-gray-950/50', 'text-gray-400');
                    productSelect.classList.add('bg-white', 'dark:bg-gray-955', 'text-gray-900', 'dark:text-white');

                    productsData[selectedCategory].forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.value;
                        option.textContent = item.text;
                        if (item.value === oldProductValue) {
                            option.selected = true;
                        }
                        productSelect.appendChild(option);
                    });

                    if (selectedCategory === 'konveksi') {
                        sizeWrapper.classList.remove('hidden');
                        sizeInput.required = true;
                    } else {
                        sizeWrapper.classList.add('hidden');
                        sizeInput.required = false;
                        sizeInput.value = '';
                    }
                } else {
                    productSelect.disabled = true;
                    productSelect.classList.add('bg-gray-50/50', 'dark:bg-gray-955/50', 'text-gray-400');
                    productSelect.classList.remove('bg-white', 'dark:bg-gray-955', 'text-gray-900', 'dark:text-white');
                    sizeWrapper.classList.add('hidden');
                    sizeInput.required = false;
                }

                handleProductChange(productSelect.value);
            }

            function handleProductChange(selectedValue) {
                if (selectedValue === 'lainnya') {
                    otherProductWrapper.classList.remove('hidden');
                    otherProductInput.required = true;
                } else {
                    otherProductWrapper.classList.add('hidden');
                    otherProductInput.required = false;
                    otherProductInput.value = '';
                }
            }

            categorySelect.addEventListener('change', function () {
                handleCategoryChange(this.value);
            });

            productSelect.addEventListener('change', function () {
                handleProductChange(this.value);
            });

            const oldCategory = "{{ old('category') }}";
            const oldProduct = "{{ old('product') }}";

            if (oldCategory) {
                handleCategoryChange(oldCategory, oldProduct);
            }
        });
    </script>
</x-app-layout>
