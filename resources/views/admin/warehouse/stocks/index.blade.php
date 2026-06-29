@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Kelola Stok Barang</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-1">Update dan monitor stok produk</p>
</div>

<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800" x-data="{ editStockId: null }">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.warehouse.stocks') }}" class="flex gap-4">
            <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} class="rounded border-gray-300">
                <span class="text-sm text-gray-700 dark:text-gray-300">Stok Rendah</span>
            </label>
            <button type="submit" class="rounded-lg bg-gray-600 px-6 py-2 text-white font-medium hover:bg-gray-700">Cari</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">SKU</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Stok</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-bold {{ $product->stock <= 10 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($product->unit) }}</td>
                        <td class="px-6 py-4 text-sm space-x-3">
                            <a href="{{ route('admin.warehouse.stocks.logs', $product) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">History</a>
                            <button @click="editStockId = {{ $product->id }}" class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">Update</button>
                        </td>
                    </tr>

                    <!-- Update Stock Modal -->
                    <tr x-show="editStockId === {{ $product->id }}" x-cloak class="bg-blue-50 dark:bg-blue-950/40">
                        <td colspan="5" class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.warehouse.stocks.update', $product) }}" class="grid grid-cols-5 gap-3">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity_change" placeholder="Jumlah perubahan" required class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                <select name="action" required class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="added">Ditambah</option>
                                    <option value="removed">Diambil</option>
                                    <option value="adjustment">Penyesuaian</option>
                                </select>
                                <input type="text" name="reason" placeholder="Alasan" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-white text-sm font-medium hover:bg-green-700">Simpan</button>
                                <button type="button" @click="editStockId = null" class="rounded-lg bg-gray-600 px-4 py-2 text-white text-sm font-medium hover:bg-gray-700">Batal</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada produk</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
        {{ $products->links() }}
    </div>
</div>
@endsection
