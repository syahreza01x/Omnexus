@extends('admin.layouts.app')

@section('breadcrumbs', 'Web Management / Kelola FAQ')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Manajemen FAQ Chatbot</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Atur pertanyaan dan jawaban yang akan diajarkan ke Chatbot Interco.</p>
        </div>
        <button x-data @click="$dispatch('open-faq-modal', { mode: 'create' })" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm shadow-purple-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah FAQ
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $faq->order }}</td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $faq->question }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $faq->answer }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($faq->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap">
                                <button x-data @click="$dispatch('open-faq-modal', { mode: 'edit', faq: {{ json_encode($faq) }} })" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</button>
                                <form action="{{ route('admin.web.faqs.destroy', $faq) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus FAQ ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data FAQ yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Dialog Alpine.js -->
<div x-data="{ 
        open: false, 
        mode: 'create', 
        faq: { id: '', question: '', answer: '', order: 0, is_active: true },
        get formAction() {
            return this.mode === 'create' ? '{{ route('admin.web.faqs.store') }}' : '{{ url('admin/web/faqs') }}/' + this.faq.id;
        }
    }" 
    @open-faq-modal.window="open = true; mode = $event.detail.mode; if(mode === 'edit') faq = { ...$event.detail.faq, is_active: !!$event.detail.faq.is_active }; else faq = { id: '', question: '', answer: '', order: 0, is_active: true };"
    x-show="open" 
    class="fixed inset-0 z-50 overflow-y-auto" 
    style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" @click="open = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900 dark:bg-black opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white dark:bg-gray-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            
            <form :action="formAction" method="POST">
                @csrf
                <template x-if="mode === 'edit'">
                    @method('PATCH')
                </template>

                <div class="px-6 py-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" x-text="mode === 'create' ? 'Tambah FAQ Baru' : 'Edit FAQ'"></h3>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                        <input type="text" name="question" x-model="faq.question" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <p class="mt-1 text-xs text-gray-500">Gunakan bahasa natural seolah bertanya kepada bot (Contoh: "Halo min, cara order gimana?").</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jawaban <span class="text-red-500">*</span></label>
                        <textarea name="answer" x-model="faq.answer" required rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Urutan</label>
                            <input type="number" name="order" x-model="faq.order" class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                        <div class="flex-1 flex items-center pt-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" x-model="faq.is_active" class="rounded text-purple-600 focus:ring-purple-500 bg-gray-100 border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktifkan FAQ</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-3">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
