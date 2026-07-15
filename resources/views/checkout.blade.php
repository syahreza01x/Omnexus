<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout — Interco</title>
    <meta name="description" content="Selesaikan pesanan Anda di Interco.">

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
            --accent-dark: #5b21b6;
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

        /* ─── Layout ─── */
        .checkout-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ─── Top Bar ─── */
        .checkout-topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .dark .checkout-topbar {
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

        /* ─── Page ─── */
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 32px;
            padding: 40px 0 80px;
            align-items: start;
        }

        /* ─── Cards ─── */
        .checkout-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 20px;
        }
        .dark .checkout-card {
            background: var(--surface-2);
        }
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-title-icon {
            width: 36px;
            height: 36px;
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

        /* ─── Cart Item ─── */
        .checkout-item {
            display: flex;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }
        .checkout-item:last-child {
            border-bottom: none;
        }
        .checkout-item-img {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--surface-2);
        }
        .checkout-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ─── Address Option ─── */
        .address-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            border: 2px solid var(--border);
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            margin-bottom: 10px;
        }
        .address-option:hover {
            border-color: rgba(124,58,237,0.3);
        }
        .address-option.selected {
            border-color: #7c3aed;
            background: rgba(124,58,237,0.04);
        }
        .dark .address-option.selected {
            background: rgba(124,58,237,0.08);
        }
        .address-radio {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
            transition: all 0.2s;
        }
        .address-option.selected .address-radio {
            border-color: #7c3aed;
        }
        .address-radio-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #7c3aed;
            transform: scale(0);
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .address-option.selected .address-radio-dot {
            transform: scale(1);
        }
        .address-label-badge {
            display: inline-flex;
            padding: 2px 8px;
            background: rgba(124,58,237,0.1);
            color: #7c3aed;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .dark .address-label-badge {
            background: rgba(124,58,237,0.2);
            color: #a78bfa;
        }

        /* ─── Textarea ─── */
        .form-textarea {
            width: 100%;
            padding: 14px 16px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 14px;
            color: var(--text);
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
            resize: none;
        }
        .form-textarea::placeholder { color: var(--muted); }
        .form-textarea:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
        }

        /* ─── Summary Card ─── */
        .summary-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            position: sticky;
            top: 88px;
        }
        .dark .summary-card {
            background: var(--surface-2);
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        .summary-row-label {
            font-size: 0.88rem;
            color: var(--muted);
        }
        .summary-row-value {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text);
        }
        .summary-total {
            border-top: 2px solid var(--border);
            margin-top: 8px;
            padding-top: 16px;
        }
        .summary-total .summary-row-value {
            font-size: 1.3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #a78bfa, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ─── Buttons ─── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 15px 28px;
            background: #7c3aed;
            color: white;
            font-weight: 700;
            font-size: 0.92rem;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            width: 100%;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 100%);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(124,58,237,0.4);
            background: #6d28d9;
        }
        .btn-primary:active { transform: translateY(0); }

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

        /* ─── Alert ─── */
        .alert-error {
            padding: 14px 18px;
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 14px;
            color: #ef4444;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .no-address-box {
            padding: 28px;
            text-align: center;
            border: 2px dashed var(--border);
            border-radius: 14px;
        }
        .no-address-box a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            padding: 10px 20px;
            background: rgba(124,58,237,0.1);
            color: #7c3aed;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .no-address-box a:hover {
            background: #7c3aed;
            color: white;
        }

        /* ─── Steps ─── */
        .checkout-steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 8px;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--muted);
        }
        .step.active {
            color: #7c3aed;
        }
        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            background: var(--surface-2);
            border: 1px solid var(--border);
        }
        .step.active .step-num {
            background: #7c3aed;
            color: white;
            border-color: #7c3aed;
        }
        .step-line {
            width: 40px;
            height: 2px;
            background: var(--border);
            margin: 0 10px;
        }

        /* ─── Delivery Method Toggle ─── */
        .method-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .method-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 22px 16px;
            border: 2px solid var(--border);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            background: var(--surface);
        }
        .dark .method-option {
            background: var(--surface-2);
        }
        .method-option::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 120%, rgba(124,58,237,0.08) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .method-option:hover {
            border-color: rgba(124,58,237,0.3);
            transform: translateY(-2px);
        }
        .method-option:hover::before {
            opacity: 1;
        }
        .method-option.selected {
            border-color: #7c3aed;
            background: rgba(124,58,237,0.04);
            box-shadow: 0 4px 20px rgba(124,58,237,0.12);
        }
        .dark .method-option.selected {
            background: rgba(124,58,237,0.1);
            box-shadow: 0 4px 20px rgba(124,58,237,0.15);
        }
        .method-option.selected::before {
            opacity: 1;
        }
        .method-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface-2);
            color: var(--muted);
            transition: all 0.3s;
            position: relative;
            z-index: 1;
        }
        .method-option.selected .method-icon {
            background: rgba(124,58,237,0.12);
            color: #7c3aed;
        }
        .dark .method-option.selected .method-icon {
            background: rgba(124,58,237,0.2);
            color: #a78bfa;
        }
        .method-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            position: relative;
            z-index: 1;
        }
        .method-desc {
            font-size: 0.76rem;
            color: var(--muted);
            text-align: center;
            line-height: 1.4;
            position: relative;
            z-index: 1;
        }
        .method-check {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #7c3aed;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: scale(0);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .method-option.selected .method-check {
            transform: scale(1);
        }

        /* ─── Info Banner ─── */
        .info-banner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: rgba(124,58,237,0.06);
            border: 1px solid rgba(124,58,237,0.15);
            border-radius: 14px;
            margin-top: 16px;
        }
        .dark .info-banner {
            background: rgba(124,58,237,0.1);
            border-color: rgba(124,58,237,0.2);
        }

        /* ─── Slide Transition ─── */
        .slide-enter {
            animation: slideDown 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-12px); max-height: 0; }
            to { opacity: 1; transform: translateY(0); max-height: 600px; }
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
        .animate-delay-4 { animation-delay: 0.4s; }

        /* ═══════ RESPONSIVE ═══════ */
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 24px 0 60px;
            }
            .summary-card {
                position: static;
            }
            .checkout-card {
                padding: 20px;
                border-radius: 16px;
            }
            .summary-card {
                padding: 20px;
                border-radius: 16px;
            }
            .method-options {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            .method-option {
                padding: 16px 12px;
            }
            .method-icon {
                width: 44px;
                height: 44px;
            }
            .checkout-steps .step span {
                display: none;
            }
            .checkout-steps .step-line {
                width: 24px;
                margin: 0 6px;
            }
            .topbar-inner {
                padding: 0 16px;
            }
            .checkout-container {
                padding: 0 16px;
            }
        }

        @media (max-width: 480px) {
            .address-option {
                padding: 12px;
            }
            .checkout-item-img {
                width: 52px;
                height: 52px;
            }
        }
    </style>
</head>
<body>
    {{-- ─── Top Bar ─── --}}
    <header class="checkout-topbar">
        <div class="topbar-inner">
            <a href="{{ route('beranda') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #7c3aed; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('images/icon.png') }}" alt="Interco" style="height: 20px; width: 20px; object-fit: contain; filter: brightness(0) invert(1);">
                </div>
                <span style="font-size: 1.1rem; font-weight: 800; color: var(--text); font-family: 'Playfair Display', serif;">Interco</span>
            </a>

            <div class="checkout-steps">
                <div class="step active">
                    <div class="step-num">1</div>
                    <span>Checkout</span>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-num">2</div>
                    <span>Pembayaran</span>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-num">3</div>
                    <span>Selesai</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <button @click="darkMode = !darkMode" style="padding: 10px; border-radius: 10px; border: 1px solid var(--border); background: transparent; cursor: pointer; color: var(--muted); transition: all 0.2s;" onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
            </div>
        </div>
    </header>

    <div class="checkout-container">
        {{-- Breadcrumb --}}
        <div style="padding-top: 28px; margin-bottom: 28px;" class="animate-in">
            <a href="{{ route('beranda') }}" class="btn-back">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="alert-error animate-in">
                <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.store') }}" x-data="{
            shippingMethod: 'pickup',
            selectedAddress: {{ $addresses->where('is_default', true)->first()?->id ?? ($addresses->first()?->id ?? 'null') }}
        }">
            @csrf
            <input type="hidden" name="shipping_method" x-bind:value="shippingMethod">

            <div class="checkout-grid">
                {{-- Left Column --}}
                <div>
                    {{-- Items --}}
                    <div class="checkout-card animate-in animate-delay-1">
                        <div class="card-title">
                            <div class="card-title-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            </div>
                            Ringkasan Pesanan
                            <span style="margin-left: auto; font-family: 'Inter', sans-serif; font-size: 0.8rem; font-weight: 600; color: var(--muted);">{{ $cartItems->count() }} item</span>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="checkout-item">
                                <div class="checkout-item-img">
                                    <img src="{{ asset($item['product']->image_path ?: 'images/items/1.png') }}" alt="{{ $item['product']->name }}">
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--text); margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $item['product']->name }}
                                        @if(!empty($item['size']) && $item['product']->category !== 'Aksesoris')
                                            <span style="font-size: 0.65rem; background: var(--border); padding: 2px 6px; border-radius: 6px; margin-left: 6px; vertical-align: middle;">Ukuran {{ $item['size'] }}</span>
                                        @endif
                                    </h4>
                                    <p style="font-size: 0.8rem; color: var(--muted); margin: 0 0 6px;">{{ $item['product']->category ?? 'Produk' }} · {{ $item['quantity'] }} × Rp {{ number_format($item['subtotal'] / max(1, $item['quantity']), 0, ',', '.') }}</p>
                                </div>
                                <div style="text-align: right; flex-shrink: 0;">
                                    <span style="font-size: 0.95rem; font-weight: 800; color: var(--text);">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Delivery Method --}}
                    <div class="checkout-card animate-in animate-delay-2">
                        <div class="card-title">
                            <div class="card-title-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-3.375c0-.621-.504-1.125-1.125-1.125h-3.527c-.392-.767-.893-1.484-1.498-2.127L13.5 8.25H7.5v5.25M3.75 14.25h3.75"/></svg>
                            </div>
                            Metode Pengambilan
                        </div>

                        <div class="method-options">
                            {{-- Pickup Option --}}
                            <div class="method-option"
                                :class="{ 'selected': shippingMethod === 'pickup' }"
                                @click="shippingMethod = 'pickup'">
                                <div class="method-check">
                                    <svg style="width: 12px; height: 12px; color: white;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </div>
                                <div class="method-icon">
                                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/></svg>
                                </div>
                                <span class="method-label">Jemput di Tempat</span>
                                <span class="method-desc">Ambil langsung di toko kami</span>
                            </div>

                            {{-- Delivery Option --}}
                            <div class="method-option"
                                :class="{ 'selected': shippingMethod === 'delivery' }"
                                @click="shippingMethod = 'delivery'">
                                <div class="method-check">
                                    <svg style="width: 12px; height: 12px; color: white;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </div>
                                <div class="method-icon">
                                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-3.375c0-.621-.504-1.125-1.125-1.125h-3.527c-.392-.767-.893-1.484-1.498-2.127L13.5 8.25H7.5v5.25M3.75 14.25h3.75"/></svg>
                                </div>
                                <span class="method-label">Dikirim</span>
                                <span class="method-desc">Kirim ke alamat pilihan Anda</span>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Address (only shown when delivery is selected) --}}
                    <div x-show="shippingMethod === 'delivery'"
                         x-transition:enter="slide-enter"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="checkout-card animate-in animate-delay-3">
                        <div class="card-title">
                            <div class="card-title-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            Alamat Pengiriman
                        </div>

                        <input type="hidden" name="address_id" x-bind:value="selectedAddress">

                        @if($addresses->count() > 0)
                            @foreach($addresses as $address)
                                <div class="address-option"
                                    :class="{ 'selected': selectedAddress === {{ $address->id }} }"
                                    @click="selectedAddress = {{ $address->id }}">
                                    <div class="address-radio">
                                        <div class="address-radio-dot"></div>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                            @if($address->label)
                                                <span class="address-label-badge">{{ $address->label }}</span>
                                            @endif
                                            @if($address->is_default)
                                                <span style="font-size: 0.68rem; font-weight: 700; color: #10b981; background: rgba(16,185,129,0.1); padding: 2px 8px; border-radius: 6px;">UTAMA</span>
                                            @endif
                                        </div>
                                        <p style="font-size: 0.88rem; font-weight: 600; color: var(--text); margin: 0 0 4px;">{{ $address->address_line }}</p>
                                        <p style="font-size: 0.82rem; color: var(--muted); margin: 0;">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                        @if($address->phone)
                                            <p style="font-size: 0.8rem; color: var(--muted); margin: 4px 0 0;">📞 {{ $address->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-address-box">
                                <svg style="width: 36px; height: 36px; color: var(--border); margin: 0 auto 8px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                <p style="font-size: 0.9rem; color: var(--muted); margin: 0;">Belum ada alamat tersimpan</p>
                                <a href="{{ route('profile.edit') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    Tambah Alamat di Profil
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Notes --}}
                    <div class="checkout-card animate-in animate-delay-4">
                        <div class="card-title">
                            <div class="card-title-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                            </div>
                            Catatan (Opsional)
                        </div>
                        <textarea name="notes" rows="3" class="form-textarea" placeholder="Tambahkan catatan untuk pesanan Anda, misalnya warna, ukuran, atau instruksi khusus...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Right Column — Summary --}}
                <div>
                    <div class="summary-card animate-in animate-delay-2">
                        <div class="card-title" style="margin-bottom: 16px;">
                            <div class="card-title-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg>
                            </div>
                            Rincian Pembayaran
                        </div>

                        <div class="summary-row">
                            <span class="summary-row-label">Subtotal ({{ $cartItems->count() }} item)</span>
                            <span class="summary-row-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-row-label">Pengambilan</span>
                            <span style="font-size: 0.82rem; font-weight: 600; color: #10b981;" x-text="shippingMethod === 'pickup' ? 'Jemput di Tempat' : 'Dikirim'"></span>
                        </div>
                        <div class="summary-row" x-show="shippingMethod === 'delivery'">
                            <span class="summary-row-label">Ongkos Kirim</span>
                            <span style="font-size: 0.82rem; font-weight: 600; color: #10b981;">Dihitung kemudian</span>
                        </div>

                        <div class="summary-row summary-total">
                            <span class="summary-row-label" style="font-weight: 700; color: var(--text);">Total</span>
                            <span class="summary-row-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn-primary" style="margin-top: 20px;" :disabled="shippingMethod === 'delivery' && {{ $addresses->count() }} === 0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Buat Pesanan
                        </button>

                        <div class="info-banner">
                            <svg style="width: 20px; height: 20px; color: #7c3aed; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                            <p style="font-size: 0.8rem; color: var(--muted); line-height: 1.5; margin: 0;">Setelah pesanan dibuat, Anda akan mendapatkan instruksi pembayaran. Pembayaran diverifikasi manual oleh admin.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
