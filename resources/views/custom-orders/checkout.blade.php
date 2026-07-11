<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Checkout Custom Order
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold font-playfair text-gray-900 dark:text-white mb-8">Checkout Custom Order</h2>

        <form method="POST" action="{{ route('custom-orders.process-checkout', $customOrder) }}" class="space-y-6">
            @csrf

            <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-800">
                <h3 class="text-lg font-bold mb-4 border-b pb-2 dark:border-zinc-800">Alamat Pengiriman</h3>
                @if($addresses->isEmpty())
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-500 rounded-xl mb-4">
                        Anda belum memiliki alamat pengiriman. Silakan tambah alamat terlebih dahulu di profil Anda.
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn-primary inline-flex">Tambah Alamat Baru</a>
                @else
                    <div class="space-y-3">
                        @foreach($addresses as $address)
                            <label class="flex items-start gap-4 p-4 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors {{ $address->is_default ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/10' : 'border-gray-200 dark:border-zinc-700' }}">
                                <div class="pt-1">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" required {{ $address->is_default ? 'checked' : '' }} class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $address->label }}</span>
                                        @if($address->is_default)
                                            <span class="px-2 py-0.5 bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400 rounded text-xs font-semibold">Utama</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $address->recipient_name }} | {{ $address->phone }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $address->address_line }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('address_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-800">
                <h3 class="text-lg font-bold mb-4 border-b pb-2 dark:border-zinc-800">Ringkasan Pesanan</h3>
                
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ asset($customOrder->product->image_path ?: 'images/items/1.png') }}" class="w-16 h-16 rounded-xl object-cover border">
                    <div class="flex-1">
                        <div class="font-bold text-gray-900 dark:text-white">{{ $customOrder->product->name }} (Custom)</div>
                        <div class="text-sm text-gray-500">{{ $customOrder->quantity }} pcs x Rp {{ number_format($customOrder->price_per_item, 0, ',', '.') }}</div>
                    </div>
                    <div class="font-bold text-violet-600 text-lg">
                        Rp {{ number_format($customOrder->quantity * $customOrder->price_per_item, 0, ',', '.') }}
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t dark:border-zinc-800">
                    <label class="form-label">Catatan untuk Pengiriman (Opsional)</label>
                    <textarea name="notes" rows="2" class="form-input" placeholder="Cth: Titip di pos satpam..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('custom-orders.show', $customOrder) }}" class="px-6 py-3 rounded-xl border border-gray-200 dark:border-zinc-700 font-semibold hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold shadow-lg shadow-violet-500/30 transition-all" {{ $addresses->isEmpty() ? 'disabled' : '' }}>
                    Buat Pesanan & Bayar
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
