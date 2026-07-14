<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pesanan #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }} — Interco</title>
    <meta name="description" content="Detail pesanan Anda di Interco.">

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

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 28px;
            align-items: start;
        }

        /* ─── Cards ─── */
        .detail-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 20px;
        }
        .dark .detail-card {
            background: var(--surface-2);
        }
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-title-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(124,58,237,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7c3aed;
            flex-shrink: 0;
        }
        .dark .card-title-icon {
            background: rgba(124,58,237,0.2);
            color: #a78bfa;
        }

        /* ─── Order Item ─── */
        .detail-item {
            display: flex;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-item-img {
            width: 68px;
            height: 68px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--surface-2);
        }
        .detail-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ─── Status Badge ─── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .status-pending { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
        .status-paid { background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); }
        .status-processing { background: rgba(124,58,237,0.1); color: #7c3aed; border: 1px solid rgba(124,58,237,0.2); }
        .status-shipped { background: rgba(14,165,233,0.1); color: #0ea5e9; border: 1px solid rgba(14,165,233,0.2); }
        .status-completed { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
        .status-cancelled { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

        /* ─── Timeline ─── */
        .timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .timeline-item {
            display: flex;
            gap: 14px;
            position: relative;
            padding-bottom: 24px;
        }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-dot-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }
        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--border);
            flex-shrink: 0;
            margin-top: 4px;
        }
        .timeline-dot.active {
            background: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124,58,237,0.15);
        }
        .timeline-dot.done {
            background: #10b981;
        }
        .timeline-line {
            width: 2px;
            flex: 1;
            background: var(--border);
            margin-top: 4px;
        }
        .timeline-content {
            padding-top: 0;
        }
        .timeline-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2px;
        }
        .timeline-date {
            font-size: 0.78rem;
            color: var(--muted);
        }
        .timeline-item.inactive .timeline-label {
            color: var(--muted);
        }

        /* ─── Info Row ─── */
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-size: 0.85rem;
            color: var(--muted);
        }
        .info-value {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            text-align: right;
        }

        /* ─── Payment Info ─── */
        .payment-box {
            padding: 20px;
            background: rgba(124,58,237,0.04);
            border: 1px solid rgba(124,58,237,0.12);
            border-radius: 16px;
        }
        .dark .payment-box {
            background: rgba(124,58,237,0.08);
            border-color: rgba(124,58,237,0.2);
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

        /* ─── Total Section ─── */
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0 0;
            border-top: 2px solid var(--border);
            margin-top: 8px;
        }
        .total-value {
            font-size: 1.3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #a78bfa, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }

        /* ═══════ RESPONSIVE ═══════ */
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .detail-card {
                padding: 20px;
                border-radius: 16px;
            }
            .detail-card[style*="sticky"] {
                position: static !important;
            }
            .page-container {
                padding: 24px 16px 60px;
            }
            .topbar-inner {
                padding: 0 16px;
            }
            .detail-item-img {
                width: 56px;
                height: 56px;
            }
            .total-value {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    @php
        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $statusOrder = ['pending', 'paid', 'processing', 'shipped', 'completed'];
        $currentIndex = array_search($transaction->status, $statusOrder);
        $isCancelled = $transaction->status === 'cancelled';
    @endphp

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
            </div>
        </div>
    </header>

    <div class="page-container">
        {{-- Breadcrumb --}}
        <div style="margin-bottom: 24px;" class="animate-in">
            <a href="{{ route('orders.index') }}" class="btn-back">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Kembali ke Riwayat Pesanan
            </a>
        </div>

        {{-- Page Title --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 12px;" class="animate-in">
            <div>
                <h1 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: var(--text); margin: 0 0 6px;">
                    Pesanan #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                </h1>
                <p style="font-size: 0.88rem; color: var(--muted); margin: 0;">
                    Dibuat pada {{ $transaction->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>
            <span class="status-badge status-{{ $transaction->status }}">
                {{ $statusLabels[$transaction->status] ?? $transaction->status }}
            </span>
        </div>

        <div class="detail-grid">
            {{-- Left Column --}}
            <div>
                {{-- Order Items --}}
                <div class="detail-card animate-in animate-delay-1">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        Produk Pesanan
                    </div>

                    @foreach($transaction->items as $item)
                        <div class="detail-item">
                            <div class="detail-item-img">
                                <img src="{{ asset($item->product?->image_path ?: 'images/items/1.png') }}" alt="{{ $item->product?->name }}">
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--text); margin: 0 0 4px;">{{ $item->product?->name ?? 'Produk dihapus' }}</h4>
                                <p style="font-size: 0.82rem; color: var(--muted); margin: 0;">
                                    {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div style="text-align: right; flex-shrink: 0;">
                                <span style="font-size: 0.95rem; font-weight: 800; color: var(--text);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach

                    {{-- Subtotal / Total --}}
                    <div class="info-row" style="border-bottom: none; padding-top: 16px;">
                        <span class="info-label">Subtotal</span>
                        <span class="info-value">Rp {{ number_format($transaction->items->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span style="font-size: 0.92rem; font-weight: 700; color: var(--text);">Total Pembayaran</span>
                        <span class="total-value">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="detail-card animate-in animate-delay-2">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        {{ $transaction->shipping_method === 'pickup' ? 'Metode Pengambilan' : 'Alamat Pengiriman' }}
                    </div>

                    @if($transaction->shipping_method === 'pickup')
                        <div style="padding: 20px; background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.15); border-radius: 14px; text-align: center;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16,185,129,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg style="width: 24px; height: 24px; color: #10b981;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/></svg>
                            </div>
                            <p style="font-size: 0.95rem; font-weight: 700; color: #10b981; margin: 0 0 4px;">Jemput di Tempat</p>
                            <p style="font-size: 0.82rem; color: var(--muted); margin: 0;">Pesanan akan diambil langsung di toko</p>
                        </div>
                    @else
                        <div style="padding: 16px; background: var(--surface-2); border-radius: 14px; border: 1px solid var(--border);">
                            @if($transaction->shipping_name)
                                <p style="font-size: 0.92rem; font-weight: 700; color: var(--text); margin: 0 0 4px;">{{ $transaction->shipping_name }}</p>
                            @endif
                            @if($transaction->shipping_phone)
                                <p style="font-size: 0.82rem; color: var(--muted); margin: 0 0 8px;">📞 {{ $transaction->shipping_phone }}</p>
                            @endif
                            <p style="font-size: 0.88rem; color: var(--text); margin: 0 0 4px; line-height: 1.5;">{{ $transaction->shipping_address }}</p>
                            <p style="font-size: 0.85rem; color: var(--muted); margin: 0;">
                                {{ $transaction->shipping_city }}, {{ $transaction->shipping_province }} {{ $transaction->shipping_postal_code }}
                            </p>
                        </div>
                    @endif

                    @if($transaction->notes)
                        <div style="margin-top: 16px; padding: 14px 16px; background: rgba(124,58,237,0.04); border: 1px solid rgba(124,58,237,0.1); border-radius: 12px;">
                            <p style="font-size: 0.78rem; font-weight: 700; color: var(--muted); letter-spacing: 0.04em; text-transform: uppercase; margin: 0 0 6px;">Catatan</p>
                            <p style="font-size: 0.88rem; color: var(--text); margin: 0; line-height: 1.6;">{{ $transaction->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                {{-- Status Timeline --}}
                <div class="detail-card animate-in animate-delay-2" style="position: sticky; top: 88px;">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        Status Pesanan
                    </div>

                    @if($isCancelled)
                        <div style="padding: 20px; background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.15); border-radius: 14px; text-align: center;">
                            <svg style="width: 32px; height: 32px; color: #ef4444; margin: 0 auto 8px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p style="font-size: 0.92rem; font-weight: 700; color: #ef4444; margin: 0 0 4px;">Pesanan Dibatalkan</p>
                            <p style="font-size: 0.82rem; color: var(--muted); margin: 0;">Pesanan ini telah dibatalkan</p>
                        </div>
                    @else
                        <div class="timeline">
                            @foreach($statusOrder as $i => $status)
                                @php
                                    $isDone = $currentIndex !== false && $i < $currentIndex;
                                    $isActive = $currentIndex !== false && $i === $currentIndex;
                                    $isInactive = !$isDone && !$isActive;
                                    $statusDates = [
                                        'pending' => $transaction->created_at,
                                        'paid' => $transaction->paid_at,
                                        'processing' => null,
                                        'shipped' => $transaction->shipped_at,
                                        'completed' => $transaction->completed_at,
                                    ];
                                @endphp
                                <div class="timeline-item {{ $isInactive ? 'inactive' : '' }}">
                                    <div class="timeline-dot-wrap">
                                        <div class="timeline-dot {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}"></div>
                                        @if(!$loop->last)
                                            <div class="timeline-line" style="{{ $isDone ? 'background: #10b981;' : '' }}"></div>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-label">{{ $statusLabels[$status] ?? $status }}</div>
                                        @if($statusDates[$status])
                                            <div class="timeline-date">{{ $statusDates[$status]->format('d M Y, H:i') }}</div>
                                        @elseif($isInactive)
                                            <div class="timeline-date">—</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Payment Info --}}
                    @if($transaction->status === 'pending')
                        <div class="payment-box" style="margin-top: 24px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                                <svg style="width: 22px; height: 22px; color: #7c3aed;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                <span style="font-size: 0.92rem; font-weight: 700; color: var(--text);">Instruksi Pembayaran</span>
                            </div>
                            <p style="font-size: 0.85rem; color: var(--muted); line-height: 1.7; margin: 0 0 14px;">
                                Silakan lakukan pembayaran sebesar:
                            </p>
                            <div style="padding: 14px; background: var(--surface); border-radius: 12px; border: 1px solid var(--border); text-align: center; margin-bottom: 14px;">
                                <span style="font-size: 1.4rem; font-weight: 800; background: linear-gradient(135deg, #a78bfa, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <div style="font-size: 0.82rem; color: var(--muted); line-height: 1.7;">
                                <p style="margin: 0 0 8px;">📌 Transfer ke salah satu rekening berikut:</p>
                                <div style="padding: 10px 14px; background: var(--surface); border-radius: 10px; border: 1px solid var(--border); margin-bottom: 6px;">
                                    <span style="font-weight: 700; color: var(--text);">BCA</span> — 1234567890 a.n. Interco
                                </div>
                                <div style="padding: 10px 14px; background: var(--surface); border-radius: 10px; border: 1px solid var(--border); margin-bottom: 12px;">
                                    <span style="font-weight: 700; color: var(--text);">Mandiri</span> — 0987654321 a.n. Interco
                                </div>
                            <p style="margin: 0; font-size: 0.78rem;">⏳ Konfirmasi pembayaran akan diverifikasi oleh admin kami.</p>
                        </div>
                    </div>
                @endif
                
                @if($transaction->status === 'pending' && !$transaction->payment_proof_path)
                    <div class="payment-box" style="margin-top: 24px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                            <svg style="width: 22px; height: 22px; color: #7c3aed;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            <span style="font-size: 0.92rem; font-weight: 700; color: var(--text);">Upload Bukti Transfer</span>
                        </div>
                        <form action="{{ route('orders.upload-proof', $transaction) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="payment_proof" accept="image/*" required style="margin-bottom: 10px; width: 100%;">
                            <button type="submit" style="width: 100%; padding: 10px; background: #7c3aed; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Upload Bukti</button>
                        </form>
                    </div>
                @elseif($transaction->payment_proof_path)
                    <div class="payment-box" style="margin-top: 24px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                            <span style="font-size: 0.92rem; font-weight: 700; color: var(--text);">Bukti Transfer Anda</span>
                        </div>
                        <a href="{{ asset('storage/' . $transaction->payment_proof_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $transaction->payment_proof_path) }}" alt="Bukti Transfer" style="max-width: 100%; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 10px;">
                        </a>
                        @if($transaction->status === 'pending')
                            <div style="padding: 10px; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); border-radius: 8px; color: #f59e0b; font-size: 0.85rem; font-weight: 600; text-align: center;">
                                Bukti transfer berhasil diunggah. Menunggu verifikasi admin.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</body>
</html>
