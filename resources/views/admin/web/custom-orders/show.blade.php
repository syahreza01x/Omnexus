@extends('admin.layouts.app')

@section('content')
<div class="px-6 py-8" x-data="{
    scrollToBottom() {
        const container = document.getElementById('chat-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
}" x-init="scrollToBottom()">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
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
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Set Harga / Item (Rp)</label>
                    <input type="number" name="price_per_item" value="{{ $customOrder->price_per_item }}" class="w-32 px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Update Status</label>
                    <select name="status" class="px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="pending" {{ $customOrder->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="designing" {{ $customOrder->status == 'designing' ? 'selected' : '' }}>Designing</option>
                        <option value="revision" {{ $customOrder->status == 'revision' ? 'selected' : '' }}>Revision</option>
                        <option value="approved" {{ $customOrder->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $customOrder->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ $customOrder->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Simpan
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl dark:bg-green-900/20 dark:border-green-900 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Order Details --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-800 pb-2">Informasi Pemesan</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Nama Pelanggan</div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $customOrder->user->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Email</div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $customOrder->user->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-800 pb-2">Detail Pesanan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Basis Produk</div>
                            <div class="font-medium text-gray-900 dark:text-white flex items-center gap-2 mt-1">
                                <img src="{{ asset($customOrder->product->image_path ?: 'images/items/1.png') }}" class="w-8 h-8 rounded object-cover border dark:border-gray-700">
                                {{ $customOrder->product->name }}
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Kategori</div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $customOrder->category ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Bahan</div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $customOrder->material ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Jumlah</div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $customOrder->quantity }} pcs</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Harga per Item</div>
                                <div class="font-medium text-purple-600 dark:text-purple-400 font-bold">
                                    {{ $customOrder->price_per_item ? 'Rp ' . number_format($customOrder->price_per_item, 0, ',', '.') : 'Belum ditentukan' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Desain Awal (Dari Pelanggan)</div>
                            @if($customOrder->user_design_path)
                                <a href="{{ asset('storage/' . $customOrder->user_design_path) }}" target="_blank" class="mt-2 block border dark:border-gray-700 rounded-xl overflow-hidden hover:opacity-80 transition-opacity">
                                    <img src="{{ asset('storage/' . $customOrder->user_design_path) }}" class="w-full h-auto object-cover max-h-48">
                                </a>
                            @else
                                <div class="font-medium text-gray-900 dark:text-white">-</div>
                            @endif
                        </div>

                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Catatan</div>
                            <div class="font-medium text-gray-900 dark:text-white p-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm mt-1">
                                {{ $customOrder->notes ?: '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Chat Interface --}}
            <div class="lg:col-span-2 flex flex-col bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 h-[800px]">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50 rounded-t-2xl">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Ruang Chat & Revisi
                    </h3>
                </div>
                
                <div id="chat-container" class="flex-1 p-6 overflow-y-auto space-y-4">
                    @if($customOrder->revisions->isEmpty())
                        <div class="h-full flex flex-col items-center justify-center text-gray-400">
                            <p>Belum ada diskusi.</p>
                            <p class="text-sm mt-1">Kirim pesan pertama kepada pelanggan!</p>
                        </div>
                    @else
                        @foreach($customOrder->revisions as $rev)
                            @if($rev->sender_type === 'admin')
                                {{-- Admin Message (Right) --}}
                                <div class="flex flex-col items-end">
                                    <div class="max-w-[80%] bg-purple-600 text-white rounded-2xl rounded-tr-sm p-4 shadow-sm">
                                        @if($rev->message)
                                            <p class="whitespace-pre-wrap text-sm mb-2">{{ $rev->message }}</p>
                                        @endif
                                        @if($rev->attachment_path)
                                            <a href="{{ asset('storage/' . $rev->attachment_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $rev->attachment_path) }}" class="rounded-lg max-h-48 object-cover border border-white/20">
                                            </a>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 mr-1">Anda • {{ $rev->created_at->format('H:i, d M') }}</span>
                                </div>
                            @else
                                {{-- User Message (Left) --}}
                                <div class="flex flex-col items-start">
                                    <div class="max-w-[80%] bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                                        @if($rev->message)
                                            <p class="whitespace-pre-wrap text-sm mb-2">{{ $rev->message }}</p>
                                        @endif
                                        @if($rev->attachment_path)
                                            <a href="{{ asset('storage/' . $rev->attachment_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $rev->attachment_path) }}" class="rounded-lg max-h-48 object-cover border border-black/10 dark:border-white/10">
                                            </a>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 ml-1">{{ $customOrder->user->name }} • {{ $rev->created_at->format('H:i, d M') }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                {{-- Chat Input Form --}}
                @if(in_array($customOrder->status, ['pending', 'designing', 'revision']))
                    <div class="p-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 rounded-b-2xl">
                        <form method="POST" action="{{ route('admin.web.custom-orders.revisions.store', $customOrder) }}" enctype="multipart/form-data" x-data="{ fileName: '', preview: null }">
                            @csrf
                            <div class="flex gap-3">
                                <div class="relative shrink-0 flex items-start">
                                    <div class="cursor-pointer p-2.5 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/30 rounded-xl transition-colors border border-transparent hover:border-purple-200" @click="$refs.fileInput.click()" :class="preview ? 'bg-purple-50 dark:bg-purple-900/20 border-purple-200' : ''">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!preview"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        <img :src="preview" class="w-8 h-8 object-cover rounded" x-show="preview" style="display: none;">
                                    </div>
                                    <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-0.5 shadow-md hover:bg-red-600 transition-colors" x-show="preview" style="display: none;" @click.prevent="preview = null; fileName = ''; $refs.fileInput.value = ''">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <input type="file" name="attachment_file" class="sr-only" accept="image/*" x-ref="fileInput"
                                        @change="
                                            const file = $event.target.files[0];
                                            if(file) {
                                                fileName = file.name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => preview = e.target.result;
                                                reader.readAsDataURL(file);
                                            } else {
                                                preview = null;
                                                fileName = '';
                                            }
                                        ">
                                </div>
                                
                                <div class="flex-1 flex flex-col">
                                    <textarea name="message" rows="2" placeholder="Tulis balasan atau lampirkan hasil desain..." class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none text-gray-900 dark:text-gray-100"></textarea>
                                    <div class="text-xs text-purple-600 mt-1.5 font-medium flex items-center gap-1" x-show="fileName" style="display: none;" x-transition>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span x-text="'File terpilih: ' + fileName"></span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start shrink-0">
                                    <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl font-semibold transition-colors h-11">
                                        Kirim
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center ml-12">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_final_design" value="1" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 bg-white dark:bg-gray-900 dark:border-gray-700">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Tandai lampiran ini sebagai <strong>Hasil Desain Final</strong> untuk disetujui pelanggan.</span>
                                </label>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-4 border-t border-gray-200 dark:border-gray-800 text-center text-sm text-gray-500 bg-gray-50 dark:bg-gray-800/50 rounded-b-2xl">
                        Diskusi telah ditutup karena pesanan sudah {{ $customOrder->status === 'approved' ? 'disetujui' : 'selesai/dibatalkan' }}.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
