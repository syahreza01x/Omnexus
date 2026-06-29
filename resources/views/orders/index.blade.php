<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pesanan — Interco</title>
    <meta name="description" content="Lihat riwayat pesanan Anda di Interco.">

    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --accent: #7c3aed;
            --accent-light: #a78bfa;
            --surface: #ffffff;
            --surface-2: #f9fafb;
            --border: #e5e7eb;
            --text: #111827;
            --muted: #6b7280;
        }
        .dark {
            --surface: #0f0f13;
            --surface-2: #18181d;
            --border: #27272a;
            --text: #f4f4f5;
            --muted: #71717a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--surface);
            color: var(--text);
            transition: background 0.3s, color 0.3s;
            margin: 0;
        }

        [x-cloak] { display: none !important; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }

        /* ─── Top Bar ─── */
        .page-topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(20px);
        }
        .dark .page-topbar {
            background: rgba(15,15,19,0.92);
        }
        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ─── Layout ─── */
        .page-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 24px 80px;
        }

        .page-header {
            margin-bottom: 32px;
        }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 8px;
        }
        .page-subtitle {
            font-size: 0.92rem;
            color: var(--muted);
            margin: 0;
        }

        /* ─── Order Card ─── */
        .order-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 16px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .dark .order-card {
            background: var(--surface-2);
        }
        .order-card:hover {
            border-color: rgba(124,58,237,0.3);
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        }
        .dark .order-card:hover {
            box-shadow: 0 12px 32px rgba(0,0,0,0.3);
        }

        .order-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .order-id {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 0.02em;
        }
        .order-date {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .order-items-preview {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }
        .order-item-thumb {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            overflow: hidden;
            background: var(--surface-2);
            border: 1px solid var(--border);
            flex-shrink: 0;
        }
        .order-item-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .order-more-items {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--muted);
        }

        .order-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        /* ─── Status Badges ─── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .status-pending {
            background: rgba(245,158,11,0.1);
            color: #f59e0b;
            border: 1px solid rgba(245,158,11,0.2);
        }
        .status-paid {
            background: rgba(59,130,246,0.1);
            color: #3b82f6;
            border: 1px solid rgba(59,130,246,0.2);
        }
        .status-processing {
            background: rgba(124,58,237,0.1);
            color: #7c3aed;
            border: 1px solid rgba(124,58,237,0.2);
        }
        .status-shipped {
            background: rgba(14,165,233,0.1);
            color: #0ea5e9;
            border: 1px solid rgba(14,165,233,0.2);
        }
        .status-completed {
            background: rgba(16,185,129,0.1);
            color: #10b981;
            border: 1px solid rgba(16,185,129,0.2);
        }
        .status-cancelled {
            background: rgba(239,68,68,0.1);
            color: #ef4444;
            border: 1px solid rgba(239,68,68,0.2);
        }

        .order-total {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text);
        }

        .order-view-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #7c3aed;
            transition: gap 0.2s;
        }
        .order-card:hover .order-view-link {
            gap: 8px;
        }

        /* ─── Empty State ─── */
        .empty-state {
            text-align: center;
            padding: 80px 24px;
            border: 2px dashed var(--border);
            border-radius: 20px;
        }

        /* ─── Pagination ─── */
        .pagination-wrap {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 32px;
        }
        .pagination-wrap a,
        .pagination-wrap span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--muted);
        }
        .pagination-wrap a:hover {
            border-color: rgba(124,58,237,0.4);
            color: #7c3aed;
        }
        .pagination-wrap .active {
            background: #7c3aed;
            color: white;
            border-color: #7c3aed;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back:hover {
            color: var(--text);
            gap: 8px;
        }

        /* ─── Success Alert ─── */
        .alert-success {
            padding: 14px 18px;
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 14px;
            color: #10b981;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @php
            function getStatusLabel($status) {
                return match($status) {
                    'pending' => 'Menunggu Pembayaran',
                    'paid' => 'Sudah Dibayar',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $status,
                };
            }
        @endphp
    </style>
</head>
<body>
    {{-- ─── Top Bar ─── --}}
    <header class="page-topbar">
        <div class="topbar-inner">
            <a href="{{ route('beranda') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #7c3aed; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('images/icon.png') }}" alt="Interco" style="height: 20px; width: 20px; object-fit: contain; filter: brightness(0) invert(1);">
                </div>
                <span style="font-size: 1.1rem; font-weight: 800; color: var(--text); font-family: 'Playfair Display', serif;">Interco</span>
            </a>

            <div style="display: flex; align-items: center; gap: 8px;">
                <button @click="darkMode = !darkMode" style="padding: 10px; border-radius: 10px; border: 1px solid var(--border); background: transparent; cursor: pointer; color: var(--muted); transition: all 0.2s;" onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <a href="{{ route('beranda') }}" style="padding: 8px 16px; border-radius: 10px; border: 1px solid var(--border); background: transparent; color: var(--muted); font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                    <svg style="width: 14px; height: 14px; display: inline; vertical-align: -2px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Beranda
                </a>
            </div>
        </div>
    </header>

    <div class="page-container">
        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert-success animate-in">
                <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="page-header animate-in">
            <h1 class="page-title">Riwayat Pesanan</h1>
            <p class="page-subtitle">Pantau status semua pesanan Anda di sini</p>
        </div>

        @forelse($transactions as $i => $transaction)
            <a href="{{ route('orders.show', $transaction) }}" class="order-card animate-in" style="animation-delay: {{ min($i * 0.05, 0.3) }}s">
                <div class="order-header">
                    <span class="order-id">ORDER #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="order-date">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>

                {{-- Item thumbnails --}}
                <div class="order-items-preview">
                    @foreach($transaction->items->take(4) as $item)
                        <div class="order-item-thumb">
                            <img src="{{ asset($item->product?->image_path ?: 'images/items/1.png') }}" alt="{{ $item->product?->name }}">
                        </div>
                    @endforeach
                    @if($transaction->items->count() > 4)
                        <div class="order-more-items">+{{ $transaction->items->count() - 4 }}</div>
                    @endif
                    <div style="margin-left: 8px;">
                        <p style="font-size: 0.85rem; font-weight: 600; color: var(--text); margin: 0;">{{ $transaction->items->count() }} produk</p>
                        <p style="font-size: 0.78rem; color: var(--muted); margin: 2px 0 0;">{{ $transaction->items->sum('quantity') }} item total</p>
                    </div>
                </div>

                <div class="order-footer">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="status-badge status-{{ $transaction->status }}">
                            @switch($transaction->status)
                                @case('pending')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="5"/></svg>
                                    Menunggu Pembayaran
                                    @break
                                @case('paid')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Sudah Dibayar
                                    @break
                                @case('processing')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                    Diproses
                                    @break
                                @case('shipped')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                    Dikirim
                                    @break
                                @case('completed')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Selesai
                                    @break
                                @case('cancelled')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Dibatalkan
                                    @break
                            @endswitch
                        </span>
                        <span class="order-total">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <span class="order-view-link">
                        Lihat Detail
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </span>
                </div>
            </a>
        @empty
            <div class="empty-state animate-in">
                <svg style="width: 48px; height: 48px; color: var(--border); margin: 0 auto 16px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                <p style="font-size: 1rem; font-weight: 600; color: var(--text); margin: 0 0 4px;">Belum ada pesanan</p>
                <p style="font-size: 0.88rem; color: var(--muted); margin: 0 0 20px;">Mulai belanja dan temukan produk favorit Anda</p>
                <a href="{{ route('beranda') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 12px 24px; background: #7c3aed; color: white; font-weight: 700; font-size: 0.88rem; border-radius: 12px; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(124,58,237,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/></svg>
                    Mulai Belanja
                </a>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="pagination-wrap">
                @if($transactions->onFirstPage())
                    <span style="opacity: 0.4;">←</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}">←</a>
                @endif

                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}">→</a>
                @else
                    <span style="opacity: 0.4;">→</span>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
