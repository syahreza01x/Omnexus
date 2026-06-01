@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Tambah Produk Baru</h1>
</div>

<div class="max-w-2xl rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
    <form method="POST" action="{{ route('admin.web.products.store') }}" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk</label>
            <input id="name" name="name" type="text" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="sku" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
                <input id="sku" name="sku" type="text" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                @error('sku')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="price" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Harga (Rp)</label>
                <input id="price" name="price" type="number" step="0.01" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                @error('price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="unit" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
            <select id="unit" name="unit" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                <option value="piece">Piece (Pcs)</option>
                <option value="kg">Kilogram (Kg)</option>
                <option value="meter">Meter (m)</option>
                <option value="liter">Liter (L)</option>
                <option value="dozen">Dozen</option>
                <option value="box">Box</option>
            </select>
        </div>

        <div>
            <label for="category" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
            <input id="category" name="category" type="text" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
        </div>

        <div>
            <label for="description" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
            <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"></textarea>
        </div>

        <div>
            <label for="specifications" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Spesifikasi</label>
            <textarea id="specifications" name="specifications" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"></textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="rounded-lg bg-purple-600 px-6 py-2 text-white font-medium hover:bg-purple-700">
                Simpan Produk
            </button>
            <a href="{{ route('admin.web.products') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 font-medium hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
