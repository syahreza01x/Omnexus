<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Custom Order
        </h2>
    </x-slot>
<div class="py-12" x-data="{
    scrollToBottom() {
        const container = document.getElementById('chat-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
}" x-init="scrollToBottom()">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('custom-orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
                <h2 class="text-2xl font-bold font-playfair text-gray-900 dark:text-white">Pesanan Custom #CUST-{{ str_pad($customOrder->id, 4, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div>
                @if($customOrder->status === 'approved')
                    <a href="{{ route('custom-orders.checkout', $customOrder) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-xl transition-colors">
                        Lanjut ke Pembayaran
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Details --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-800">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2 dark:border-zinc-800">Detail Permintaan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-gray-500">Basis Pakaian</div>
                            <div class="font-medium flex items-center gap-2">
                                <img src="{{ asset($customOrder->product->image_path ?: 'images/items/1.png') }}" class="w-8 h-8 rounded object-cover">
                                {{ $customOrder->product->name }}
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Kategori</div>
                                <div class="font-medium">{{ $customOrder->category ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Bahan</div>
                                <div class="font-medium">{{ $customOrder->material ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Jumlah</div>
                                <div class="font-medium">{{ $customOrder->quantity }} pcs</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Harga per Item</div>
                                <div class="font-medium font-bold text-violet-600">
                                    {{ $customOrder->price_per_item ? 'Rp ' . number_format($customOrder->price_per_item, 0, ',', '.') : 'Belum ditentukan' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500">Desain Awal (Dari Kamu)</div>
                            @if($customOrder->user_design_path)
                                <a href="{{ asset('storage/' . $customOrder->user_design_path) }}" target="_blank" class="mt-2 block border rounded-xl overflow-hidden hover:opacity-80 transition-opacity">
                                    <img src="{{ asset('storage/' . $customOrder->user_design_path) }}" class="w-full h-auto object-cover max-h-48">
                                </a>
                            @else
                                <div class="font-medium">-</div>
                            @endif
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-500">Catatan</div>
                            <div class="font-medium p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-lg text-sm mt-1">
                                {{ $customOrder->notes ?: '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Admin Design Result --}}
                @if($customOrder->admin_design_path)
                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-violet-200 dark:border-violet-900/50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-3 bg-violet-100 dark:bg-violet-900/30 rounded-bl-xl text-violet-600 dark:text-violet-400 font-bold text-xs uppercase tracking-wider">
                            Hasil Desain
                        </div>
                        <h3 class="text-lg font-bold mb-4">Preview dari Admin</h3>
                        <a href="{{ asset('storage/' . $customOrder->admin_design_path) }}" target="_blank" class="block rounded-xl overflow-hidden shadow">
                            <img src="{{ asset('storage/' . $customOrder->admin_design_path) }}" class="w-full h-auto">
                        </a>
                        
                        @if($customOrder->status === 'designing' || $customOrder->status === 'revision')
                            <form method="POST" action="{{ route('custom-orders.approve', $customOrder) }}" class="mt-4">
                                @csrf
                                <button type="submit" class="w-full py-2.5 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl transition-colors">
                                    Saya Setuju dengan Desain Ini (Acc)
                                </button>
                                <p class="text-xs text-center text-gray-500 mt-2">Menyetujui berarti desain sudah final dan siap diproduksi.</p>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Right: Chat / Revisions --}}
            <div class="lg:col-span-2 flex flex-col bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-800 h-[800px]">
                <div class="p-4 border-b border-gray-200 dark:border-zinc-800 flex justify-between items-center bg-gray-50 dark:bg-zinc-800/50 rounded-t-2xl">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Diskusi & Revisi
                    </h3>
                    <div class="text-xs font-semibold px-3 py-1 rounded-full bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-300">
                        Status: {{ strtoupper($customOrder->status) }}
                    </div>
                </div>
                
                <div id="chat-container" class="flex-1 p-6 overflow-y-auto space-y-4">
                    @if($customOrder->revisions->isEmpty())
                        <div class="h-full flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p>Belum ada pesan. Admin akan merespon segera.</p>
                        </div>
                    @else
                        @foreach($customOrder->revisions as $rev)
                            @if($rev->sender_type === 'user')
                                {{-- User Message (Right) --}}
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
                                {{-- Admin Message (Left) --}}
                                <div class="flex flex-col items-start">
                                    <div class="max-w-[80%] bg-gray-100 dark:bg-zinc-800 text-gray-800 dark:text-gray-200 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                                        @if($rev->message)
                                            <p class="whitespace-pre-wrap text-sm mb-2">{{ $rev->message }}</p>
                                        @endif
                                        @if($rev->attachment_path)
                                            <a href="{{ asset('storage/' . $rev->attachment_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $rev->attachment_path) }}" class="rounded-lg max-h-48 object-cover border border-black/10 dark:border-white/10">
                                            </a>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-400 mt-1 ml-1">Admin Interco • {{ $rev->created_at->format('H:i, d M') }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                {{-- Chat Input Form --}}
                @if(in_array($customOrder->status, ['pending', 'designing', 'revision']))
                    <div class="p-4 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 rounded-b-2xl">
                        <form method="POST" action="{{ route('custom-orders.revisions.store', $customOrder) }}" enctype="multipart/form-data" class="flex gap-2">
                            @csrf
                            <label class="cursor-pointer p-3 text-gray-500 hover:text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/30 rounded-xl transition-colors shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <input type="file" name="attachment_file" class="hidden" accept="image/*">
                            </label>
                            
                            <textarea name="message" rows="2" placeholder="Tulis balasan atau minta revisi..." class="flex-1 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500 resize-none"></textarea>
                            
                            <button type="submit" class="px-5 bg-violet-600 hover:bg-violet-500 text-white rounded-xl font-semibold transition-colors shrink-0">
                                Kirim
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-4 border-t border-gray-200 dark:border-zinc-800 text-center text-sm text-gray-500 bg-gray-50 dark:bg-zinc-800/50 rounded-b-2xl">
                        Diskusi telah ditutup karena pesanan sudah {{ $customOrder->status === 'approved' ? 'disetujui' : 'selesai/dibatalkan' }}.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
