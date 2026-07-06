<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('custom-orders.index') }}" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:bg-gray-150 dark:hover:bg-gray-700 transition duration-150 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-3xl bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 dark:from-purple-400 dark:via-violet-400 dark:to-indigo-400 bg-clip-text text-transparent leading-tight">
                        Detail Custom Order
                    </h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">ID Pesanan: #ORD-{{ str_pad($customOrder->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
            <span class="inline-flex px-4 py-1.5 rounded-2xl text-xs font-bold uppercase tracking-wider {{ $customOrder->status_badge_classes }} shadow-sm">
                {{ $customOrder->status_label }}
            </span>
        </div>
    </x-slot>

    @php
        $statusStepMap = [
            'menunggu_review' => 0,
            'menunggu_persetujuan_customer' => 1,
            'diproses' => 2,
            'produksi' => 3,
            'siap_diambil_dikirim' => 4,
            'selesai' => 5,
        ];
        $currentStepIndex = $statusStepMap[$customOrder->status] ?? 0;

        $steps = [
            ['title' => 'Order Dibuat', 'desc' => 'Pesanan berhasil dikirim ke sistem.'],
            ['title' => 'Direview Admin', 'desc' => 'Admin meninjau spesifikasi order.'],
            ['title' => 'Menunggu Persetujuan', 'desc' => 'Silakan konfirmasi detail pesanan Anda.'],
            ['title' => 'Diproses', 'desc' => 'Antrean pesanan disetujui & diproses.'],
            ['title' => 'Produksi', 'desc' => 'Barang sedang diproduksi di workshop.'],
            ['title' => 'Siap Diambil / Dikirim', 'desc' => 'Pesanan selesai & siap diambil/dikirim.'],
            ['title' => 'Selesai', 'desc' => 'Transaksi selesai.'],
        ];
    @endphp

<<<<<<< Updated upstream
    <div class="py-12 bg-gray-50/50 dark:bg-gray-955/20">
=======
    <div class="py-12 bg-gray-50/50 dark:bg-gray-955/20" x-data="{ showRevisionModal: false }">
>>>>>>> Stashed changes
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ─── Timeline Stepper Card ─── --}}
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-800 p-6 md:p-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-55/70 dark:border-gray-800 pb-3">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Timeline Status Pesanan
                </h3>

                {{-- Desktop Horizontal Stepper --}}
                <div class="hidden lg:block relative my-10 px-8">
                    <div class="absolute top-1/2 left-10 right-10 h-1 bg-gray-100 dark:bg-gray-800 -translate-y-1/2 z-0"></div>
                    
                    @php
                        $progressPercent = ($currentStepIndex / 5) * 100;
                        if($currentStepIndex === 0) $progressPercent = 16.66;
                    @endphp
                    <div class="absolute top-1/2 left-10 h-1 bg-gradient-to-r from-purple-500 to-indigo-500 -translate-y-1/2 z-0 transition-all duration-700 ease-in-out" style="width: calc({{ $progressPercent }}% - 20px);"></div>

                    <div class="relative z-10 flex justify-between">
                        {{-- Step 1: Order Dibuat --}}
                        <div class="flex flex-col items-center w-24 text-center">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20 ring-4 ring-white dark:ring-gray-900 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white mt-3">Order Dibuat</span>
                        </div>

                        {{-- Step 2: Direview Admin --}}
                        <div class="flex flex-col items-center w-24 text-center">
                            @if($currentStepIndex >= 1)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20 ring-4 ring-white dark:ring-gray-900 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white mt-3">Direview Admin</span>
                            @else
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-white border-2 border-yellow-500 text-yellow-600 dark:bg-gray-900 font-bold ring-4 ring-white dark:ring-gray-900 animate-pulse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-xs font-bold text-yellow-600 dark:text-yellow-400 mt-3">Direview Admin</span>
                            @endif
                        </div>

                        {{-- Step 3: Menunggu Persetujuan --}}
                        <div class="flex flex-col items-center w-24 text-center">
                            @if($currentStepIndex >= 2)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20 ring-4 ring-white dark:ring-gray-900 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white mt-3">Disetujui</span>
                            @elseif($currentStepIndex === 1)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-white border-2 border-orange-500 text-orange-600 dark:bg-gray-900 font-bold ring-4 ring-white dark:ring-gray-900 animate-pulse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <span class="text-xs font-bold text-orange-600 dark:text-orange-400 mt-3">Persetujuan</span>
                            @else
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-450 dark:text-gray-500 font-semibold ring-4 ring-white dark:ring-gray-900">
                                    3
                                </div>
                                <span class="text-xs text-gray-500 mt-3">Persetujuan</span>
                            @endif
                        </div>

                        {{-- Step 4: Diproses --}}
                        <div class="flex flex-col items-center w-24 text-center">
                            @if($currentStepIndex >= 3)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20 ring-4 ring-white dark:ring-gray-900 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white mt-3">Diproses</span>
                            @elseif($currentStepIndex === 2)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-white border-2 border-blue-500 text-blue-600 dark:bg-gray-900 font-bold ring-4 ring-white dark:ring-gray-900 animate-pulse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                </div>
                                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 mt-3">Diproses</span>
                            @else
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-450 dark:text-gray-500 font-semibold ring-4 ring-white dark:ring-gray-900">
                                    4
                                </div>
                                <span class="text-xs text-gray-500 mt-3">Diproses</span>
                            @endif
                        </div>

                        {{-- Step 5: Produksi --}}
                        <div class="flex flex-col items-center w-24 text-center">
                            @if($currentStepIndex >= 4)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20 ring-4 ring-white dark:ring-gray-900 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white mt-3">Produksi</span>
                            @elseif($currentStepIndex === 3)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-white border-2 border-indigo-500 text-indigo-600 dark:bg-gray-900 font-bold ring-4 ring-white dark:ring-gray-900 animate-pulse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                </div>
                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mt-3">Produksi</span>
                            @else
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-450 dark:text-gray-500 font-semibold ring-4 ring-white dark:ring-gray-900">
                                    5
                                </div>
                                <span class="text-xs text-gray-500 mt-3">Produksi</span>
                            @endif
                        </div>

                        {{-- Step 6: Siap Diambil / Dikirim --}}
                        <div class="flex flex-col items-center w-24 text-center">
                            @if($currentStepIndex >= 5)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20 ring-4 ring-white dark:ring-gray-900 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white mt-3">Siap Diambil</span>
                            @elseif($currentStepIndex === 4)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-white border-2 border-purple-500 text-purple-600 dark:bg-gray-900 font-bold ring-4 ring-white dark:ring-gray-900 animate-pulse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                </div>
                                <span class="text-xs font-bold text-purple-600 dark:text-purple-400 mt-3">Siap Kirim</span>
                            @else
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-450 dark:text-gray-500 font-semibold ring-4 ring-white dark:ring-gray-900">
                                    6
                                </div>
                                <span class="text-xs text-gray-500 mt-3">Siap Kirim</span>
                            @endif
                        </div>

                        {{-- Step 7: Selesai --}}
                        <div class="flex flex-col items-center w-24 text-center">
                            @if($currentStepIndex === 5)
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-500/20 ring-4 ring-white dark:ring-gray-900 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-bold text-emerald-650 dark:text-emerald-450 mt-3">Selesai</span>
                            @else
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-455 dark:text-gray-500 font-semibold ring-4 ring-white dark:ring-gray-900">
                                    7
                                </div>
                                <span class="text-xs text-gray-500 mt-3">Selesai</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Mobile Vertical Stepper --}}
                <div class="lg:hidden space-y-6 my-4 pl-4 relative border-l-2 border-gray-200 dark:border-gray-800">
                    @foreach ($steps as $index => $step)
                        @php
                            $isDone = false;
                            $isActive = false;

                            if ($index === 0) {
                                $isDone = true;
                            } elseif ($index === 1) {
                                $isDone = $currentStepIndex >= 1;
                            } else {
                                $isDone = $currentStepIndex >= $index;
                                $isActive = $currentStepIndex === ($index - 1);
                            }
                        @endphp
                        
                        <div class="relative pl-6">
                            <div class="absolute -left-[29px] top-0.5 w-6 h-6 rounded-xl flex items-center justify-center ring-4 ring-white dark:ring-gray-900 
                                @if($isDone) bg-purple-600 text-white
                                @elseif($isActive) bg-white border-2 border-yellow-500 text-yellow-600
                                @else bg-gray-200 dark:bg-gray-800 text-gray-400 dark:text-gray-550 @endif">
                                @if($isDone)
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <span class="text-[10px] font-bold">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-bold @if($isDone) text-gray-900 dark:text-white @elseif($isActive) text-yellow-600 dark:text-yellow-400 @else text-gray-400 @endif">
                                    {{ $step['title'] }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-405 mt-0.5 leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Alert Toast / Success Message --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-900/50 dark:text-emerald-400 flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-emerald-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Mockup Review Card --}}
                @if($customOrder->status === 'menunggu_persetujuan_customer' || $customOrder->status === 'revisi_desain' || $customOrder->admin_mockup_file)
                    <div class="md:col-span-2 bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-800 p-6 md:p-8 space-y-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Mockup Desain
                        </h3>

                        @if($customOrder->admin_mockup_file)
                            @php
                                $mockupExtension = pathinfo($customOrder->admin_mockup_file, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($mockupExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp

                            @if($isImage)
                                <div class="rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-955/20 p-2 flex justify-center">
                                    <img src="{{ asset('storage/' . $customOrder->admin_mockup_file) }}" alt="Mockup Desain" class="max-h-96 object-contain rounded-xl">
                                </div>
                            @else
                                <div class="flex items-center gap-3 p-4 rounded-2xl border border-purple-100/50 dark:border-purple-950/40 bg-purple-50/20 dark:bg-purple-950/10">
                                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300 truncate">
                                            {{ basename($customOrder->admin_mockup_file) }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">Berkas Mockup</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $customOrder->admin_mockup_file) }}" target="_blank" class="px-4 py-2 bg-purple-605 hover:bg-purple-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition">
                                        Unduh
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="p-6 rounded-2xl border border-dashed border-gray-250 dark:border-gray-800 text-center">
                                <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold">Mockup desain belum diunggah oleh admin.</p>
                            </div>
                        @endif

                        @if($customOrder->revision_notes)
                            <div class="mt-4 p-4 rounded-2xl bg-amber-50 border border-amber-250 text-amber-800 dark:bg-amber-950/20 dark:border-amber-900/50 dark:text-amber-400">
                                <span class="text-xs font-bold uppercase tracking-wider block mb-1">Catatan Revisi Terakhir (Revisi ke-{{ $customOrder->revision_count }}):</span>
                                <p class="text-sm leading-relaxed font-medium">{{ $customOrder->revision_notes }}</p>
                            </div>
                        @endif

                        @if($customOrder->status === 'menunggu_persetujuan_customer')
                            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                                <form action="{{ route('custom-orders.approve-mockup', $customOrder) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-emerald-500/20 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Setujui Desain & Mulai Produksi
                                    </button>
                                </form>
                                
                                <button type="button" @click="showRevisionModal = true" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-amber-500/20 focus:outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Ajukan Revisi Pesanan
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

>>>>>>> Stashed changes
                {{-- Detail Order Card --}}
                <div class="md:col-span-2 bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-800 p-6 md:p-8 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center gap-2">
                        Spesifikasi Custom Order
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Kategori Pesanan</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ ucfirst($customOrder->category) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Produk</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                @if($customOrder->product === 'lainnya')
                                    {{ $customOrder->other_product_name }} <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">(Lainnya)</span>
                                @else
                                    {{ $customOrder->product }}
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Jumlah</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ number_format($customOrder->quantity) }} Pcs
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Warna</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ $customOrder->color }}
                            </span>
                        </div>
                        
                        @if($customOrder->category === 'konveksi')
                            <div>
                                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Ukuran</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                    {{ $customOrder->size ?? '-' }}
                                </span>
                            </div>
                        @endif

                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Jenis Bahan</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ $customOrder->material_type }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Teknik Produksi</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ $customOrder->production_technique }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Deadline</span>
                            <span class="text-sm font-extrabold text-gray-900 dark:text-white mt-1 block">
                                {{ $customOrder->deadline->format('d F Y') }}
                            </span>
                        </div>
                    </div>

                    @if($customOrder->notes)
                        <div class="bg-gray-50/50 dark:bg-gray-950/20 rounded-2xl p-5 border border-gray-100 dark:border-gray-800">
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">Catatan Tambahan</span>
                            <p class="text-sm text-gray-750 dark:text-gray-300 leading-relaxed font-medium">{{ $customOrder->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Contact & File Sidebar --}}
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-800 p-6 md:p-8 space-y-6">
                    <div>
                        <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">
                            Informasi Pemesan
                        </h3>
                        <div class="space-y-4 text-sm font-medium">
                            <div>
                                <span class="text-xs font-bold text-gray-400 block uppercase tracking-wider">Nama Pemesan</span>
                                <span class="text-gray-800 dark:text-gray-200 mt-1 block">{{ $customOrder->customer_name }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 block uppercase tracking-wider">Nomor WhatsApp</span>
                                <span class="text-gray-850 dark:text-gray-200 mt-1 block font-mono">{{ $customOrder->whatsapp_number }}</span>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-800 pt-3">
                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider">Nama Akun Login</span>
                                <span class="text-xs text-gray-500 dark:text-gray-450 mt-0.5 block">{{ $customOrder->user->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-800 pt-4">
                        <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">
                            Berkas Desain
                        </h3>
                        @if ($customOrder->design_file)
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl border border-purple-100/50 dark:border-purple-950/40 bg-purple-50/20 dark:bg-purple-950/10 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    @php
                                        $fileName = basename($customOrder->design_file);
                                    @endphp
                                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate" title="{{ $fileName }}">
                                        {{ $fileName }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Berkas Terunggah</p>
                                </div>
                            </div>
                            
                            <a href="{{ route('admin.web.custom-orders.download', $customOrder) }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-purple-500/20 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh Desain
                            </a>
                        @else
                            <p class="text-xs text-red-500 font-bold">Tidak ada file desain yang diunggah.</p>
                        @endif
                    </div>
                </div>
                       {{-- ─── Modal Pengajuan Revisi ─── --}}
            <div x-show="showRevisionModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showRevisionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showRevisionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white dark:bg-gray-900 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800 p-6 md:p-8 space-y-6" @click.away="showRevisionModal = false">
                        <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">
                                Ajukan Revisi Pesanan Custom
                            </h3>
                            <button @click="showRevisionModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form action="{{ route('custom-orders.request-revision', $customOrder) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="revision_notes" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Catatan Revisi <span class="text-red-500">*</span></label>
                                <textarea name="revision_notes" id="revision_notes" rows="4" required placeholder="Tuliskan secara detail bagian desain atau spesifikasi produk yang ingin direvisi (contoh: ukuran logo diperbesar 15%, warna kaos diganti Navy, dll.)" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/25 focus:outline-none transition"></textarea>
                            </div>

                            <div>
                                <label for="new_design_file" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Upload File Acuan Baru (Opsional)</label>
                                <input type="file" name="new_design_file" id="new_design_file" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 dark:file:bg-purple-900/30 dark:file:text-purple-400 hover:file:bg-purple-100 transition">
                                <p class="text-[10px] text-gray-450 mt-1">Mendukung: JPG, JPEG, PNG, PDF, AI, CDR. Maksimal 10MB.</p>
                            </div>

                            <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
                                <button type="button" @click="showRevisionModal = false" class="px-5 py-2.5 border border-gray-250 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    Batal
                                </button>
@endif

                        @if($customOrder->revision_notes)
                            <div class="mt-4 p-4 rounded-2xl bg-amber-50 border border-amber-250 text-amber-800 dark:bg-amber-950/20 dark:border-amber-900/50 dark:text-amber-400">
                                <span class="text-xs font-bold uppercase tracking-wider block mb-1">Catatan Revisi Terakhir (Revisi ke-{{ $customOrder->revision_count }}):</span>
                                <p class="text-sm leading-relaxed font-medium">{{ $customOrder->revision_notes }}</p>
                            </div>
                        @endif

                        @if($customOrder->status === 'menunggu_persetujuan_customer')
                            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                                <form action="{{ route('custom-orders.approve-mockup', $customOrder) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-emerald-500/20 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Setujui Desain & Mulai Produksi
                                    </button>
                                </form>
                                
                                <button type="button" @click="showRevisionModal = true" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-amber-500/20 focus:outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Ajukan Revisi Pesanan
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Detail Order Card --}}
                <div class="md:col-span-2 bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-800 p-6 md:p-8 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center gap-2">
                        Spesifikasi Custom Order
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Kategori Pesanan</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ ucfirst($customOrder->category) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Produk</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                @if($customOrder->product === 'lainnya')
                                    {{ $customOrder->other_product_name }} <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">(Lainnya)</span>
                                @else
                                    {{ $customOrder->product }}
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Jumlah</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ number_format($customOrder->quantity) }} Pcs
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Warna</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ $customOrder->color }}
                            </span>
                        </div>
                        
                        @if($customOrder->category === 'konveksi')
                            <div>
                                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Ukuran</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                    {{ $customOrder->size ?? '-' }}
                                </span>
                            </div>
                        @endif

                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Jenis Bahan</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ $customOrder->material_type }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Teknik Produksi</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1 block">
                                {{ $customOrder->production_technique }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Deadline</span>
                            <span class="text-sm font-extrabold text-gray-900 dark:text-white mt-1 block">
                                {{ $customOrder->deadline->format('d F Y') }}
                            </span>
                        </div>
                    </div>

                    @if($customOrder->notes)
                        <div class="bg-gray-50/50 dark:bg-gray-950/20 rounded-2xl p-5 border border-gray-100 dark:border-gray-800">
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">Catatan Tambahan</span>
                            <p class="text-sm text-gray-750 dark:text-gray-300 leading-relaxed font-medium">{{ $customOrder->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Contact & File Sidebar --}}
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-800 p-6 md:p-8 space-y-6">
                    <div>
                        <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">
                            Informasi Pemesan
                        </h3>
                        <div class="space-y-4 text-sm font-medium">
                            <div>
                                <span class="text-xs font-bold text-gray-400 block uppercase tracking-wider">Nama Pemesan</span>
                                <span class="text-gray-800 dark:text-gray-200 mt-1 block">{{ $customOrder->customer_name }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 block uppercase tracking-wider">Nomor WhatsApp</span>
                                <span class="text-gray-850 dark:text-gray-200 mt-1 block font-mono">{{ $customOrder->whatsapp_number }}</span>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-800 pt-3">
                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider">Nama Akun Login</span>
                                <span class="text-xs text-gray-500 dark:text-gray-450 mt-0.5 block">{{ $customOrder->user->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-800 pt-4">
                        <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">
                            Berkas Desain
                        </h3>
                        @if ($customOrder->design_file)
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl border border-purple-100/50 dark:border-purple-950/40 bg-purple-50/20 dark:bg-purple-950/10 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    @php
                                        $fileName = basename($customOrder->design_file);
                                    @endphp
                                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate" title="{{ $fileName }}">
                                        {{ $fileName }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Berkas Terunggah</p>
                                </div>
                            </div>
                            
                            <a href="{{ route('admin.web.custom-orders.download', $customOrder) }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 shadow-md shadow-purple-500/20 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh Desain
                            </a>
                        @else
                            <p class="text-xs text-red-500 font-bold">Tidak ada file desain yang diunggah.</p>
                        @endif
                    </div>
                </div>
                       {{-- ─── Modal Pengajuan Revisi ─── --}}
            <div x-show="showRevisionModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showRevisionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showRevisionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white dark:bg-gray-900 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800 p-6 md:p-8 space-y-6" @click.away="showRevisionModal = false">
                        <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">
                                Ajukan Revisi Pesanan Custom
                            </h3>
                            <button @click="showRevisionModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form action="{{ route('custom-orders.request-revision', $customOrder) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="revision_notes" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Catatan Revisi <span class="text-red-500">*</span></label>
                                <textarea name="revision_notes" id="revision_notes" rows="4" required placeholder="Tuliskan secara detail bagian desain atau spesifikasi produk yang ingin direvisi (contoh: ukuran logo diperbesar 15%, warna kaos diganti Navy, dll.)" class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-955 text-gray-900 dark:text-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500/25 focus:outline-none transition"></textarea>
                            </div>

                            <div>
                                <label for="new_design_file" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Upload File Acuan Baru (Opsional)</label>
                                <input type="file" name="new_design_file" id="new_design_file" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 dark:file:bg-purple-900/30 dark:file:text-purple-400 hover:file:bg-purple-100 transition">
                                <p class="text-[10px] text-gray-450 mt-1">Mendukung: JPG, JPEG, PNG, PDF, AI, CDR. Maksimal 10MB.</p>
                            </div>

                            <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
                                <button type="button" @click="showRevisionModal = false" class="px-5 py-2.5 border border-gray-250 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-2xl text-sm transition duration-150 shadow-lg shadow-purple-500/20">
                                    Kirim Revisi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
