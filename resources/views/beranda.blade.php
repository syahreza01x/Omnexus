<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', productModalOpen: false, cartOpen: false, selectedProduct: null, navScrolled: false }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); window.addEventListener('scroll', () => { navScrolled = window.scrollY > 20 })" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interco — Wujudkan Pakaian Impianmu</title>
    <meta name="description" content="Custom order pakaian premium. Desain bebas, bahan berkualitas, pengerjaan cepat.">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ─── Base ─── */
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

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--surface);
            color: var(--text);
            transition: background 0.3s, color 0.3s;
        }

        [x-cloak] { display: none !important; }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }

        /* ─── Navbar ─── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            background: transparent;
        }
        /* Light mode: always show frosted glass (hero is dark so transparent = unreadable) */
        html:not(.dark) .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
        }
        .navbar.scrolled {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }
        .dark .navbar.scrolled {
            background: rgba(15,15,19,0.88);
        }

        /* ─── Hero ─── */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: #0a0a0f;
            position: relative;
            overflow: hidden;
            padding-top: 64px;
        }
        .hero-grain {
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            opacity: 0.5;
            pointer-events: none;
        }
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 8s ease-in-out infinite;
        }
        .hero-orb-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(124,58,237,0.25) 0%, transparent 70%);
            top: -100px; right: -100px;
            animation-delay: 0s;
        }
        .hero-orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(79,70,229,0.18) 0%, transparent 70%);
            bottom: -80px; left: -80px;
            animation-delay: 3s;
        }
        .hero-orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(167,139,250,0.12) 0%, transparent 70%);
            top: 40%; left: 30%;
            animation-delay: 5s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(124,58,237,0.15);
            border: 1px solid rgba(124,58,237,0.3);
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #a78bfa;
            backdrop-filter: blur(10px);
            animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.02em;
            color: #ffffff;
            animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
        }
        .hero-title span {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 50%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-desc {
            font-size: 1.05rem;
            color: #9ca3af;
            line-height: 1.75;
            animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }
        .hero-actions {
            animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both;
        }

        /* ─── Animations ─── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 1; }
            100% { transform: scale(1.6); opacity: 0; }
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Intersection Observer animations */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible {
            opacity: 1;
            transform: none;
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* ─── Buttons ─── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: #7c3aed;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
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

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.85);
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.15);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            backdrop-filter: blur(10px);
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        /* ─── Hero visual ─── */
        .hero-visual {
            position: relative;
            animation: fadeSlideUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
        }
        .hero-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 28px;
            backdrop-filter: blur(20px);
            transition: all 0.4s ease;
        }
        .hero-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(124,58,237,0.3);
            transform: translateY(-4px);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #a78bfa, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ─── Section headers ─── */
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: rgba(124,58,237,0.1);
            border: 1px solid rgba(124,58,237,0.2);
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 12px;
        }
        .dark .section-label {
            background: rgba(124,58,237,0.15);
            border-color: rgba(124,58,237,0.3);
            color: #a78bfa;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        /* ─── Category cards ─── */
        .category-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 16px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
        }
        .category-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(124,58,237,0.06) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .category-card:hover::before { opacity: 1; }
        .category-card:hover {
            border-color: rgba(124,58,237,0.4);
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(124,58,237,0.12);
        }
        .dark .category-card {
            background: var(--surface-2);
        }
        .dark .category-card:hover {
            box-shadow: 0 20px 40px rgba(124,58,237,0.2);
        }
        .category-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: rgba(124,58,237,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .category-icon { background: rgba(124,58,237,0.15); }
        .category-card:hover .category-icon {
            background: #7c3aed;
            transform: scale(1.1) rotate(-5deg);
        }
        .category-icon svg { color: #7c3aed; transition: color 0.3s; }
        .dark .category-icon svg { color: #a78bfa; }
        .category-card:hover .category-icon svg { color: #ffffff; }

        /* ─── Product cards ─── */
        .product-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .product-card { background: var(--surface-2); }
        .product-card:hover {
            border-color: rgba(124,58,237,0.3);
            transform: translateY(-6px);
            box-shadow: 0 24px 48px rgba(0,0,0,0.12);
        }
        .dark .product-card:hover {
            box-shadow: 0 24px 48px rgba(0,0,0,0.4);
        }
        .product-img-wrap {
            aspect-ratio: 1/1;
            overflow: hidden;
            background: #f3f4f6;
            position: relative;
        }
        .dark .product-img-wrap { background: #27272a; }
        .product-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .product-card:hover .product-img-wrap img { transform: scale(1.08); }

        /* Quick add overlay */
        .product-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .product-card:hover .product-overlay { opacity: 1; }
        .product-overlay-btn {
            padding: 10px 20px;
            background: white;
            color: #111;
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 99px;
            border: none;
            cursor: pointer;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            transform: translateY(10px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .product-card:hover .product-overlay-btn { transform: translateY(0); }
        .product-overlay-btn:hover { background: #f3f4f6; }

        .product-category-tag {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 10px;
            background: rgba(0,0,0,0.75);
            color: white;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border-radius: 99px;
            backdrop-filter: blur(8px);
        }

        /* ─── Feature cards ─── */
        .feature-card {
            padding: 32px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .feature-card { background: var(--surface-2); }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, #7c3aed, #4f46e5);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .feature-card:hover::before { transform: scaleX(1); }
        .feature-card:hover {
            border-color: rgba(124,58,237,0.25);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
        .dark .feature-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: rgba(124,58,237,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .feature-icon { background: rgba(124,58,237,0.2); }
        .feature-card:hover .feature-icon {
            background: #7c3aed;
            transform: scale(1.1);
        }
        .feature-icon svg { color: #7c3aed; transition: color 0.3s; }
        .dark .feature-icon svg { color: #a78bfa; }
        .feature-card:hover .feature-icon svg { color: white; }

        /* ─── Review cards ─── */
        .review-card {
            padding: 28px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            position: relative;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .review-card { background: var(--surface-2); }
        .review-card:hover {
            border-color: rgba(124,58,237,0.2);
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(0,0,0,0.07);
        }
        .dark .review-card:hover { box-shadow: 0 16px 32px rgba(0,0,0,0.3); }
        .review-quote {
            position: absolute;
            top: 20px; right: 24px;
            font-size: 4rem;
            line-height: 1;
            color: rgba(124,58,237,0.1);
            font-family: serif;
            pointer-events: none;
            user-select: none;
        }
        .dark .review-quote { color: rgba(167,139,250,0.12); }

        /* ─── Feedback form ─── */
        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
        }
        .form-input::placeholder { color: var(--muted); }
        .form-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
        }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        /* ─── Cart Drawer ─── */
        .cart-drawer {
            position: absolute;
            right: 0; top: 0;
            height: 100%;
            width: min(420px, 100vw);
            background: var(--surface);
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .cart-drawer { background: #0f0f13; }
        [x-show="cartOpen"] > .cart-drawer { transform: translateX(0); }

        /* ─── Product Modal ─── */
        .modal-content {
            position: relative;
            width: min(900px, calc(100vw - 2rem));
            max-height: calc(100vh - 4rem);
            overflow: hidden;
            border-radius: 28px;
            background: var(--surface);
            box-shadow: 0 40px 80px rgba(0,0,0,0.2);
            animation: scaleIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .modal-content { background: #0f0f13; }

        /* ─── Footer ─── */
        .footer {
            background: #07070b;
            color: #9ca3af;
        }
        .dark .footer { background: #000000; }

        /* ─── Misc ─── */
        .tag-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Ticker animation for hero stats */
        .ticker { animation: float 6s ease-in-out infinite; }
        .ticker:nth-child(2) { animation-delay: 2s; }
        .ticker:nth-child(3) { animation-delay: 4s; }

        /* Shimmer loading skeleton */
        .skeleton::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.08) 50%, transparent 100%);
            animation: shimmer 1.5s infinite;
        }

        /* Cart item hover */
        .cart-item {
            border-radius: 16px;
            border: 1px solid var(--border);
            padding: 16px;
            transition: border-color 0.2s;
        }
        .cart-item:hover { border-color: rgba(124,58,237,0.3); }

        /* Nav glass pill */
        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Add to cart button pulse */
        .add-btn {
            position: relative;
        }
        .add-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: rgba(124,58,237,0.4);
            animation: pulse-ring 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        /* Gradient text */
        .grad-text {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 50%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Background section alternation */
        .bg-section-alt {
            background: var(--surface-2);
        }

        /* Search input styling */
        .search-wrap input {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.9);
        }
        .navbar.scrolled .search-wrap input {
            background: var(--surface-2);
            border-color: var(--border);
            color: var(--text);
        }
    </style>
</head>
<body>

    {{-- ─── Navbar ─── --}}
    <nav class="navbar" :class="{ 'scrolled': navScrolled }" id="navbar">
        <div class="nav-inner">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 group" style="text-decoration: none;">
                <img src="{{ asset('images/icon.png') }}" alt="Interco" class="h-8 w-8 object-contain transition-transform duration-300 group-hover:scale-105">
                <span class="text-lg font-bold" style="color: var(--text); transition: color 0.3s;" :style="(navScrolled || !darkMode) ? 'color: var(--text)' : 'color: white'">Interco</span>
            </a>

            {{-- Navigation Links --}}
            <div class="hidden md:flex items-center justify-center gap-8 flex-1">
                <a href="#" class="text-sm font-semibold transition-colors duration-200" :style="(navScrolled || !darkMode) ? 'color: var(--text)' : 'color: white'" style="text-decoration: none;">Beranda</a>
                <a href="#kategori" class="text-sm font-semibold transition-colors duration-200" :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.7)'" onmouseover="this.style.color=(navScrolled || !darkMode) ? 'var(--text)' : 'white'" onmouseout="this.style.color=(navScrolled || !darkMode) ? 'var(--muted)' : 'rgba(255,255,255,0.7)'" style="text-decoration: none;">Kategori</a>
                <a href="#produk" class="text-sm font-semibold transition-colors duration-200" :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.7)'" onmouseover="this.style.color=(navScrolled || !darkMode) ? 'var(--text)' : 'white'" onmouseout="this.style.color=(navScrolled || !darkMode) ? 'var(--muted)' : 'rgba(255,255,255,0.7)'" style="text-decoration: none;">Produk</a>
                <a href="#testimoni" class="text-sm font-semibold transition-colors duration-200" :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.7)'" onmouseover="this.style.color=(navScrolled || !darkMode) ? 'var(--text)' : 'white'" onmouseout="this.style.color=(navScrolled || !darkMode) ? 'var(--muted)' : 'rgba(255,255,255,0.7)'" style="text-decoration: none;">Testimoni</a>
            </div>

            {{-- Search --}}
            <div class="hidden md:flex flex-1 max-w-xs mx-8 search-wrap">
                <div class="relative w-full">
                    <input type="text" placeholder="Cari produk..."
                        class="w-full rounded-xl py-2.5 pl-10 pr-4 text-sm focus:outline-none transition-all duration-300"
                        style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);"
                        :style="(navScrolled || !darkMode) ? 'background: var(--surface-2); border-color: var(--border); color: var(--text)' : 'background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.12); color: rgba(255,255,255,0.9)'">
                    <svg class="absolute left-3 top-3 h-4 w-4" style="color: rgba(255,255,255,0.4);" :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.4)'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-2">
                {{-- Dark toggle --}}
                <button @click="darkMode = !darkMode"
                    class="p-2.5 rounded-xl transition-all duration-200"
                    style="color: rgba(255,255,255,0.7);"
                    :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.7)'"
                    :class="(navScrolled || !darkMode) ? 'hover:bg-gray-100 dark:hover:bg-zinc-800' : 'hover:bg-white/10'">
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- Cart (hanya tampil jika sudah login) --}}
                @auth
                <button type="button" @click="productModalOpen = false; cartOpen = true"
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl transition-all duration-200"
                    style="color: rgba(255,255,255,0.7);"
                    :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.7)'"
                    :class="(navScrolled || !darkMode) ? 'hover:bg-gray-100 dark:hover:bg-zinc-800' : 'hover:bg-white/10'">
                    @if($cartCount > 0)
                        <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-violet-600 px-1 text-[9px] font-bold text-white ring-2 ring-white dark:ring-zinc-950">{{ $cartCount }}</span>
                    @endif
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                </button>
                @endauth

                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center justify-center w-9 h-9 rounded-xl bg-violet-600 text-white text-sm font-bold hover:bg-violet-500 transition-all duration-200">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-52 rounded-2xl shadow-xl border py-2 z-50"
                            style="background: var(--surface); border-color: var(--border);">
                            <div class="px-4 py-3 border-b" style="border-color: var(--border);">
                                <p class="text-sm font-semibold truncate" style="color: var(--text)">{{ auth()->user()->name }}</p>
                                <p class="text-xs truncate" style="color: var(--muted)">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors" style="color: var(--muted);" onmouseover="this.style.background='rgba(124,58,237,0.08)'" onmouseout="this.style.background='transparent'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                            <a href="{{ route('chat.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors" style="color: var(--muted);" onmouseover="this.style.background='rgba(124,58,237,0.08)'" onmouseout="this.style.background='transparent'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Chat Support
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors" style="color: var(--muted);" onmouseover="this.style.background='rgba(124,58,237,0.08)'" onmouseout="this.style.background='transparent'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                Riwayat Pesanan
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left transition-colors" style="color: var(--muted);" onmouseover="this.style.background='rgba(239,68,68,0.08)'; this.style.color='#ef4444'" onmouseout="this.style.background='transparent'; this.style.color='var(--muted)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-primary" style="padding: 10px 20px; font-size: 0.85rem;">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ─── Hero Section ─── --}}
    <section class="hero-section">
        <div class="hero-grain"></div>
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>

        <div style="max-width: 1280px; margin: 0 auto; padding: 80px 24px; width: 100%; position: relative; z-index: 1;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center;" class="md:grid-cols-2">
                {{-- Left content --}}
                <div>
                    <div class="hero-badge">
                        <span style="width: 6px; height: 6px; background: #7c3aed; border-radius: 50%; animation: pulse 2s infinite;"></span>
                        Custom Order Premium
                    </div>

                    <h1 class="hero-title" style="margin-top: 20px; margin-bottom: 20px;">
                        Wujudkan<br>
                        <span>Pakaian Impian</span><br>
                        Anda
                    </h1>

                    <p class="hero-desc" style="max-width: 480px; margin-bottom: 36px;">
                        Desain pakaian custom sesuai keinginan Anda. Dari bahan, warna, hingga detail jahitan — semua bisa disesuaikan dengan sempurna.
                    </p>

                    <div class="hero-actions" style="display: flex; flex-wrap: wrap; gap: 12px;">
                        <a href="#produk" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                            </svg>
                            Lihat Katalog
                        </a>
                        <a href="#" class="btn-ghost">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                            </svg>
                            Custom Order
                        </a>
                    </div>

                    {{-- Mini stats --}}
                    <div style="display: flex; gap: 32px; margin-top: 56px; padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.08);">
                        <div>
                            <div style="font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #a78bfa, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">500+</div>
                            <div style="font-size: 0.78rem; color: #6b7280; margin-top: 4px; font-weight: 500;">Produk Custom</div>
                        </div>
                        <div style="width: 1px; background: rgba(255,255,255,0.08);"></div>
                        <div>
                            <div style="font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #a78bfa, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">2K+</div>
                            <div style="font-size: 0.78rem; color: #6b7280; margin-top: 4px; font-weight: 500;">Pelanggan Puas</div>
                        </div>
                        <div style="width: 1px; background: rgba(255,255,255,0.08);"></div>
                        <div>
                            <div style="font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #a78bfa, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">4.9</div>
                            <div style="font-size: 0.78rem; color: #6b7280; margin-top: 4px; font-weight: 500;">Rating Bintang</div>
                        </div>
                    </div>
                </div>

                {{-- Right: Floating cards --}}
                <div class="hero-visual" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 20px;">
                    <div class="hero-card ticker" style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(124,58,237,0.2); display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 22px; height: 22px; color: #a78bfa;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size: 0.72rem; color: #6b7280; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 4px;">Desain Bebas</div>
                            <div style="font-size: 0.9rem; font-weight: 600; color: #f4f4f5; line-height: 1.4;">Upload desain kamu atau konsultasi dengan kami</div>
                        </div>
                    </div>
                    <div class="hero-card ticker" style="display: flex; flex-direction: column; gap: 16px; margin-top: 24px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(79,70,229,0.2); display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 22px; height: 22px; color: #818cf8;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.385 3.17a.75.75 0 01-1.088-.79l1.028-5.99-4.353-4.242a.75.75 0 01.416-1.279l6.015-.874L11.065.93a.75.75 0 011.37 0l2.692 5.455 6.015.874a.75.75 0 01.416 1.28l-4.353 4.24 1.028 5.99a.75.75 0 01-1.088.791L12 15.17l-5.385 3.17z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size: 0.72rem; color: #6b7280; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 4px;">Bahan Premium</div>
                            <div style="font-size: 0.9rem; font-weight: 600; color: #f4f4f5; line-height: 1.4;">Kualitas terbaik yang nyaman dan tahan lama</div>
                        </div>
                    </div>
                    <div class="hero-card ticker" style="display: flex; flex-direction: column; gap: 16px; grid-column: span 2;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(124,58,237,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 22px; height: 22px; color: #a78bfa;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                                </svg>
                            </div>
                            <div>
                                <div style="font-size: 0.72rem; color: #6b7280; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase;">Pengiriman Cepat</div>
                                <div style="font-size: 0.9rem; font-weight: 600; color: #f4f4f5;">3–7 hari kerja ke seluruh Indonesia</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                            <div style="flex: 1; height: 4px; background: rgba(255,255,255,0.06); border-radius: 99px; overflow: hidden;">
                                <div style="width: 72%; height: 100%; background: linear-gradient(90deg, #7c3aed, #a78bfa); border-radius: 99px;"></div>
                            </div>
                            <span style="font-size: 0.72rem; color: #a78bfa; font-weight: 700;">72% Selesai</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div style="position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center; gap: 8px; animation: fadeIn 1s ease 1s both;">
            <span style="font-size: 0.7rem; color: rgba(255,255,255,0.35); letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600;">Scroll</span>
            <div style="width: 1px; height: 40px; background: linear-gradient(to bottom, rgba(255,255,255,0.3), transparent);"></div>
        </div>
    </section>

    {{-- ─── Kategori ─── --}}
    <section id="kategori" style="padding: 80px 0; background: var(--surface);">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
            <div class="reveal" style="text-align: center; margin-bottom: 48px;">
                <div class="section-label" style="margin: 0 auto 12px;">Kategori Produk</div>
                <h2 class="section-title" style="color: var(--text);">Pilih Jenis Pakaian</h2>
                <p style="color: var(--muted); margin-top: 10px; font-size: 0.95rem;">Temukan kategori yang sesuai dengan kebutuhanmu</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px;" class="reveal reveal-delay-1">
                @php
                    $categories = [
                        ['name' => 'Aksesoris', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>'],
                        ['name' => 'Pakaian', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 3l-5 4 2 2 2-1v12a1 1 0 001 1h8a1 1 0 001-1V8l2 1 2-2-5-4-1.5 2h-5L8 3z"/>'],
                        ['name' => 'Outer', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 3L4 7l1.5 1.5L7 7.5V20a1 1 0 001 1h3v-6h2v6h3a1 1 0 001-1V7.5l1.5 1L20 7l-5-4h-1l-1 2h-2L9 3z"/>'],
                        ['name' => 'Kemeja', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 3l-4 4 3 2V20a1 1 0 001 1h10a1 1 0 001-1V9l3-2-4-4-2 3h-6L7 3zM10 3v4l2 1 2-1V3"/>'],
                        ['name' => 'Rompi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 3l-2 3v14a1 1 0 001 1h8a1 1 0 001-1V6l-2-3h-6zM9 3c0 2 1.5 3 3 3s3-1 3-3"/>'],
                    ];
                @endphp
                @foreach($categories as $cat)
                    <a href="#" class="category-card">
                        <div class="category-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                {!! $cat['icon'] !!}
                            </svg>
                        </div>
                        <span style="font-size: 0.82rem; font-weight: 700; color: var(--text); transition: color 0.3s; letter-spacing: 0.02em;">{{ $cat['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── Produk Custom ─── --}}
    <section id="produk" class="bg-section-alt" style="padding: 80px 0;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
            <div class="reveal" style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 48px;">
                <div>
                    <div class="section-label">Koleksi Terpopuler</div>
                    <h2 class="section-title" style="color: var(--text);">Produk Custom Pilihan</h2>
                    <p style="color: var(--muted); margin-top: 8px; font-size: 0.95rem;">Inspirasi desain dari pesanan pelanggan kami</p>
                </div>
                <a href="#" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 600; color: #7c3aed; text-decoration: none; transition: gap 0.2s;" onmouseover="this.style.gap='10px'" onmouseout="this.style.gap='6px'">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                @forelse($products as $i => $product)
                    @php
                        $productData = \Illuminate\Support\Js::from([
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'price' => (int) $product->price,
                            'price_formatted' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                            'image' => asset($product->image_path ?: 'images/items/1.png'),
                            'category' => $product->category,
                            'specifications' => $product->specifications,
                            'stock' => $product->stock,
                            'unit' => $product->unit,
                        ]);
                    @endphp
                    <div class="product-card reveal reveal-delay-{{ min($i + 1, 4) }}"
                        @click="cartOpen = false; selectedProduct = {{ $productData }}; productModalOpen = true">
                        <div class="product-img-wrap">
                            @if($product->category)
                                <div class="product-category-tag">{{ $product->category }}</div>
                            @endif
                            <img src="{{ asset($product->image_path ?: 'images/items/1.png') }}"
                                alt="{{ $product->name }}"
                                loading="lazy">
                            <div class="product-overlay">
                                <button class="product-overlay-btn" type="button">Lihat Detail</button>
                            </div>
                        </div>
                        <div style="padding: 16px 18px 18px;">
                            <h3 style="font-size: 0.9rem; font-weight: 700; color: var(--text); margin-bottom: 4px; line-height: 1.3;">{{ $product->name }}</h3>
                            <p style="font-size: 0.78rem; color: var(--muted); margin-bottom: 14px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $product->description }}</p>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-size: 1rem; font-weight: 800; color: var(--text);">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <button type="button"
                                    @click.stop="cartOpen = false; selectedProduct = {{ $productData }}; productModalOpen = true"
                                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: rgba(124,58,237,0.1); color: #7c3aed; border: none; cursor: pointer; transition: all 0.3s;"
                                    onmouseover="this.style.background='#7c3aed'; this.style.color='white'; this.style.transform='scale(1.1)'"
                                    onmouseout="this.style.background='rgba(124,58,237,0.1)'; this.style.color='#7c3aed'; this.style.transform='scale(1)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: span 4; padding: 48px; text-align: center; border: 2px dashed var(--border); border-radius: 20px; color: var(--muted); font-size: 0.9rem;">
                        <svg style="width: 40px; height: 40px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Belum ada produk yang aktif.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ─── Kenapa Custom di Sini ─── --}}
    <section style="padding: 80px 0; background: var(--surface);">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
            <div class="reveal" style="text-align: center; margin-bottom: 52px;">
                <div class="section-label" style="margin: 0 auto 12px;">Keunggulan Kami</div>
                <h2 class="section-title" style="color: var(--text);">Kenapa Custom di Interco?</h2>
                <p style="color: var(--muted); margin-top: 10px; font-size: 0.95rem; max-width: 480px; margin-left: auto; margin-right: auto;">Kami menghadirkan pengalaman custom order yang mudah, cepat, dan berkualitas tinggi</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                <div class="feature-card reveal reveal-delay-1">
                    <div class="feature-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 10px; font-family: 'Playfair Display', serif;">Desain Bebas</h3>
                    <p style="color: var(--muted); font-size: 0.9rem; line-height: 1.7;">Upload desain sendiri atau konsultasi dengan tim desainer kami untuk hasil terbaik yang sesuai visi Anda.</p>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 20px; font-size: 0.8rem; font-weight: 600; color: #7c3aed;">
                        <span>Mulai Sekarang</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </div>
                </div>
                <div class="feature-card reveal reveal-delay-2">
                    <div class="feature-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.385 3.17a.75.75 0 01-1.088-.79l1.028-5.99-4.353-4.242a.75.75 0 01.416-1.279l6.015-.874L11.065.93a.75.75 0 011.37 0l2.692 5.455 6.015.874a.75.75 0 01.416 1.28l-4.353 4.24 1.028 5.99a.75.75 0 01-1.088.791L12 15.17l-5.385 3.17z"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 10px; font-family: 'Playfair Display', serif;">Bahan Premium</h3>
                    <p style="color: var(--muted); font-size: 0.9rem; line-height: 1.7;">Hanya menggunakan bahan berkualitas tinggi yang nyaman dipakai sepanjang hari dan tahan lama untuk berbagai kebutuhan.</p>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 20px; font-size: 0.8rem; font-weight: 600; color: #7c3aed;">
                        <span>Lihat Material</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </div>
                </div>
                <div class="feature-card reveal reveal-delay-3">
                    <div class="feature-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 10px; font-family: 'Playfair Display', serif;">Pengerjaan Cepat</h3>
                    <p style="color: var(--muted); font-size: 0.9rem; line-height: 1.7;">Proses produksi 3–7 hari kerja dengan pengiriman ekspres ke seluruh Indonesia dengan tracking realtime.</p>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 20px; font-size: 0.8rem; font-weight: 600; color: #7c3aed;">
                        <span>Cek Estimasi</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── Review Section ─── --}}
    <section id="testimoni" class="bg-section-alt" style="padding: 80px 0;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
            <div class="reveal" style="text-align: center; margin-bottom: 52px;">
                <div class="section-label" style="margin: 0 auto 12px;">Testimoni</div>
                <h2 class="section-title" style="color: var(--text);">Kata Pelanggan Kami</h2>
                <p style="color: var(--muted); margin-top: 10px; font-size: 0.95rem;">Apa yang mereka rasakan setelah berbelanja di Interco</p>
            </div>
            @php
                $reviews = [
                    ['name' => 'Andi Saputra', 'role' => 'Pengusaha', 'stars' => 5, 'desc' => 'Kualitas jahitan sangat rapi dan bahan yang digunakan benar-benar premium. Sangat puas dengan hasilnya!'],
                    ['name' => 'Rina Wati', 'role' => 'Content Creator', 'stars' => 4, 'desc' => 'Desain sesuai dengan yang saya minta. Pengiriman juga cepat. Recommended banget! Tim-nya sangat responsif.'],
                    ['name' => 'Budi Hartono', 'role' => 'Manajer Pemasaran', 'stars' => 5, 'desc' => 'Sudah 3 kali order di sini dan selalu konsisten kualitasnya. Harga juga sangat bersaing.'],
                    ['name' => 'Siti Nurhaliza', 'role' => 'Desainer Interior', 'stars' => 4, 'desc' => 'Proses konsultasi desainnya sangat membantu. Tim-nya ramah dan responsif. Pasti order lagi!'],
                    ['name' => 'Dimas Prasetyo', 'role' => 'Mahasiswa', 'stars' => 5, 'desc' => 'Jaket varsity custom saya hasilnya keren banget! Teman-teman pada nanya beli di mana. Wajib rekomen!'],
                    ['name' => 'Maya Sari', 'role' => 'Freelancer', 'stars' => 4, 'desc' => 'Bahan nyaman dipakai seharian. Bordir logonya juga detail dan presisi. Sangat memuaskan hasilnya.'],
                ];
            @endphp
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                @foreach($reviews as $i => $review)
                    <div class="review-card reveal reveal-delay-{{ ($i % 3) + 1 }}">
                        <div class="review-quote">"</div>
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(79,70,229,0.2)); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; color: #7c3aed; flex-shrink: 0;">
                                {{ strtoupper(substr($review['name'], 0, 1)) }}
                            </div>
                            <div>
                                <h4 style="font-size: 0.88rem; font-weight: 700; color: var(--text);">{{ $review['name'] }}</h4>
                                <p style="font-size: 0.75rem; color: var(--muted);">{{ $review['role'] }}</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 3px; margin-bottom: 12px;">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4" style="color: {{ $i <= $review['stars'] ? '#f59e0b' : 'var(--border)' }};" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p style="font-size: 0.88rem; color: var(--muted); line-height: 1.75; position: relative; z-index: 1;">"{{ $review['desc'] }}"</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── Feedback Form ─── --}}
    <section style="padding: 80px 0; background: var(--surface);">
        <div style="max-width: 640px; margin: 0 auto; padding: 0 24px;">
            <div class="reveal" style="text-align: center; margin-bottom: 40px;">
                <div class="section-label" style="margin: 0 auto 12px;">Suara Anda</div>
                <h2 class="section-title" style="color: var(--text);">Kritik & Saran</h2>
                <p style="color: var(--muted); margin-top: 10px; font-size: 0.95rem;">Bantu kami terus berkembang dengan masukan berharga Anda</p>
            </div>
            <form method="POST" action="#"
                x-data="{ anonim: false }"
                class="reveal reveal-delay-1"
                style="background: var(--surface-2); border: 1px solid var(--border); border-radius: 24px; padding: 36px;">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" placeholder="kamu@email.com" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Username</label>
                        <input type="text" name="username" placeholder="Nama tampilan" class="form-input" x-bind:disabled="anonim" x-bind:style="anonim ? 'opacity:0.5; cursor:not-allowed;' : ''">
                    </div>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 16px;">
                        <div style="position: relative; width: 40px; height: 22px; flex-shrink: 0;"
                            @click="anonim = !anonim">
                            <div style="width: 100%; height: 100%; border-radius: 99px; transition: background 0.2s; cursor: pointer;"
                                :style="anonim ? 'background: #7c3aed' : 'background: var(--border)'"></div>
                            <div style="position: absolute; top: 3px; width: 16px; height: 16px; border-radius: 50%; background: white; transition: left 0.2s; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"
                                :style="anonim ? 'left: 21px' : 'left: 3px'"></div>
                        </div>
                        <span style="font-size: 0.85rem; color: var(--muted); font-weight: 500;">Kirim sebagai Anonim</span>
                    </label>
                    <div>
                        <label class="form-label">Komentar</label>
                        <textarea name="komentar" rows="4" placeholder="Tulis kritik atau saran Anda di sini..." class="form-input" style="resize: none;"></textarea>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-primary" style="background: #7c3aed;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- ─── Product Detail Modal ─── --}}
    <div x-cloak x-show="productModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        {{-- Backdrop --}}
        <div class="absolute inset-0" style="background: rgba(0,0,0,0.75); backdrop-filter: blur(8px);" @click="productModalOpen = false"></div>

        <div class="modal-content" style="overflow-y: auto;">
            {{-- Close btn --}}
            <button type="button" @click="productModalOpen = false"
                style="position: absolute; top: 16px; right: 16px; z-index: 10; width: 36px; height: 36px; border-radius: 10px; background: var(--surface-2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; color: var(--muted);"
                onmouseover="this.style.background='var(--border)'"
                onmouseout="this.style.background='var(--surface-2)'">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div style="display: grid; grid-template-columns: 1fr 1fr; min-height: 500px;">
                {{-- Product image --}}
                <div style="overflow: hidden; border-radius: 28px 0 0 28px;">
                    <img x-bind:src="selectedProduct?.image" x-bind:alt="selectedProduct?.name"
                        style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;" class="modal-img">
                </div>

                {{-- Product info --}}
                <div style="padding: 40px 36px; overflow-y: auto; max-height: 80vh;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <span style="padding: 4px 12px; border-radius: 99px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; background: rgba(124,58,237,0.1); color: #7c3aed; border: 1px solid rgba(124,58,237,0.2);"
                            x-text="selectedProduct?.category || 'Produk'"></span>
                        <span style="font-size: 0.78rem; color: var(--muted);" x-text="selectedProduct ? selectedProduct.stock + ' stok tersedia' : ''"></span>
                    </div>

                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: var(--text); line-height: 1.2; margin-bottom: 12px;" x-text="selectedProduct?.name"></h3>
                    <p style="font-size: 0.9rem; line-height: 1.75; color: var(--muted); margin-bottom: 24px;" x-text="selectedProduct?.description"></p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                        <div style="padding: 16px; background: var(--surface-2); border-radius: 14px; border: 1px solid var(--border);">
                            <div style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); margin-bottom: 6px;">Harga</div>
                            <div style="font-size: 1.2rem; font-weight: 800; color: var(--text);" x-text="selectedProduct?.price_formatted"></div>
                        </div>
                        <div style="padding: 16px; background: var(--surface-2); border-radius: 14px; border: 1px solid var(--border);">
                            <div style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); margin-bottom: 6px;">Satuan</div>
                            <div style="font-size: 1.2rem; font-weight: 800; color: var(--text);" x-text="selectedProduct?.unit"></div>
                        </div>
                    </div>

                    <div style="padding: 16px; background: var(--surface-2); border-radius: 14px; border: 1px solid var(--border); margin-bottom: 24px;">
                        <div style="font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Spesifikasi</div>
                        <p style="font-size: 0.88rem; color: var(--muted); line-height: 1.7;" x-text="selectedProduct?.specifications || 'Belum ada spesifikasi tambahan.'"></p>
                    </div>

                    @auth
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" x-bind:value="selectedProduct?.id">
                        <div style="display: flex; gap: 12px; align-items: flex-end;">
                            <div>
                                <label style="display: block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Qty</label>
                                <input type="number" name="quantity" min="1" value="1"
                                    style="width: 90px; padding: 12px 14px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 12px; color: var(--text); font-size: 0.9rem; font-weight: 600; outline: none; transition: border-color 0.2s;"
                                    onfocus="this.style.borderColor='#7c3aed'"
                                    onblur="this.style.borderColor='var(--border)'">
                            </div>
                            <button type="submit" class="btn-primary" style="flex: 1; justify-content: center;">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                </svg>
                                Masukkan ke Keranjang
                            </button>
                        </div>
                    </form>
                    @else
                    {{-- Guest: redirect ke login --}}
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="padding: 14px 16px; background: rgba(124,58,237,0.06); border: 1px solid rgba(124,58,237,0.15); border-radius: 14px; display: flex; align-items: center; gap: 10px;">
                            <svg style="width: 18px; height: 18px; color: #7c3aed; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                            </svg>
                            <p style="font-size: 0.83rem; color: var(--muted); line-height: 1.5;">Kamu perlu <strong style="color: var(--text);">masuk</strong> terlebih dahulu untuk menambahkan produk ke keranjang.</p>
                        </div>
                        <a href="{{ route('login') }}" class="btn-primary" style="justify-content: center; text-align: center; text-decoration: none;">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                            </svg>
                            Masuk untuk Memesan
                        </a>
                    </div>
                    @endauth

                </div>
            </div>
        </div>
    </div>

    {{-- ─── Cart Drawer ─── --}}
    <div x-cloak x-show="cartOpen" class="fixed inset-0 z-[200]"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        {{-- Backdrop --}}
        <div class="absolute inset-0" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);" @click="cartOpen = false"></div>

        {{-- Drawer --}}
        <div class="absolute right-0 top-0 h-full overflow-y-auto"
            style="width: min(440px, 100vw); background: var(--surface); box-shadow: -20px 0 60px rgba(0,0,0,0.15);"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="transform translate-x-full"
            x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform translate-x-0"
            x-transition:leave-end="transform translate-x-full">

            {{-- Header --}}
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 24px; border-bottom: 1px solid var(--border); position: sticky; top: 0; background: var(--surface); z-index: 1; backdrop-filter: blur(20px);">
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text); font-family: 'Playfair Display', serif;">Keranjang Belanja</h3>
                    <p style="font-size: 0.8rem; color: var(--muted); margin-top: 2px;">{{ $cartCount }} item dipilih</p>
                </div>
                <button type="button" @click="cartOpen = false"
                    style="width: 36px; height: 36px; border-radius: 10px; background: var(--surface-2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted); transition: all 0.2s;"
                    onmouseover="this.style.background='var(--border)'"
                    onmouseout="this.style.background='var(--surface-2)'">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Items --}}
            <div style="padding: 20px; display: flex; flex-direction: column; gap: 12px;">
                @forelse($cart as $cartItem)
                    @php $cartProduct = $cartProducts->get((int) $cartItem['product_id']); @endphp
                    @if($cartProduct)
                        <div class="cart-item">
                            <div style="display: flex; gap: 14px;">
                                <div style="width: 72px; height: 72px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: var(--surface-2);">
                                    <img src="{{ asset($cartProduct->image_path ?: 'images/items/1.png') }}"
                                        alt="{{ $cartProduct->name }}"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h4 style="font-size: 0.88rem; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $cartProduct->name }}</h4>
                                    <p style="font-size: 0.8rem; font-weight: 600; color: #7c3aed; margin: 4px 0 12px;">Rp {{ number_format($cartProduct->price, 0, ',', '.') }}</p>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <form method="POST" action="{{ route('cart.update', $cartProduct) }}" style="display: flex; align-items: center; gap: 8px;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" min="1" value="{{ $cartItem['quantity'] }}"
                                                style="width: 60px; padding: 6px 10px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.82rem; font-weight: 600; outline: none; text-align: center;">
                                            <button type="submit"
                                                style="padding: 6px 14px; background: #7c3aed; color: white; border: none; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: background 0.2s;"
                                                onmouseover="this.style.background='#6d28d9'"
                                                onmouseout="this.style.background='#7c3aed'">Update</button>
                                        </form>
                                        <form method="POST" action="{{ route('cart.remove', $cartProduct) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="padding: 6px 12px; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                                                onmouseover="this.style.background='#ef4444'; this.style.color='white'"
                                                onmouseout="this.style.background='rgba(239,68,68,0.1)'; this.style.color='#ef4444'">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div style="text-align: center; padding: 48px 24px; border: 2px dashed var(--border); border-radius: 16px;">
                        <svg style="width: 40px; height: 40px; margin: 0 auto 12px; color: var(--border);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                        </svg>
                        <p style="font-size: 0.9rem; color: var(--muted); font-weight: 500;">Keranjang masih kosong</p>
                        <p style="font-size: 0.8rem; color: var(--muted); margin-top: 4px;">Tambahkan produk ke keranjangmu</p>
                    </div>
                @endforelse
            </div>

            {{-- Subtotal + actions --}}
            @if($cartCount > 0)
            <div style="padding: 20px; border-top: 1px solid var(--border); position: sticky; bottom: 0; background: var(--surface);">
                <div style="background: var(--surface-2); border: 1px solid var(--border); border-radius: 16px; padding: 16px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 0.85rem; color: var(--muted);">Subtotal</span>
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--text);">Rp {{ number_format($cartSubtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--muted);">Belum termasuk ongkos kirim</div>
                </div>
                <a href="{{ route('checkout') }}"
                    style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; border-radius: 12px; background: #7c3aed; color: white; font-size: 0.92rem; font-weight: 700; text-decoration: none; transition: all 0.3s; position: relative; overflow: hidden; margin-bottom: 10px;"
                    onmouseover="this.style.background='#6d28d9'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(124,58,237,0.4)'"
                    onmouseout="this.style.background='#7c3aed'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Checkout
                </a>
                <div style="display: flex; gap: 10px;">
                    <button type="button" @click="cartOpen = false"
                        style="flex: 1; padding: 12px; border-radius: 12px; border: 1px solid var(--border); background: transparent; color: var(--muted); font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='var(--surface-2)'"
                        onmouseout="this.style.background='transparent'">Lanjut Belanja</button>
                    <form method="POST" action="{{ route('cart.clear') }}" style="flex: 1;">
                        @csrf
                        <button type="submit"
                            style="width: 100%; padding: 12px; border-radius: 12px; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='#ef4444'; this.style.color='white'"
                            onmouseout="this.style.background='rgba(239,68,68,0.1)'; this.style.color='#ef4444'">Kosongkan</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ─── Footer ─── --}}
    <footer class="footer">
        <div style="max-width: 1280px; margin: 0 auto; padding: 64px 24px 32px;">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 48px; margin-bottom: 48px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #7c3aed; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('images/icon.png') }}" alt="Interco" style="width: 20px; height: 20px; object-fit: contain; filter: brightness(0) invert(1);">
                        </div>
                        <span style="font-size: 1.1rem; font-weight: 800; color: white; font-family: 'Playfair Display', serif;">Interco</span>
                    </div>
                    <p style="font-size: 0.88rem; line-height: 1.75; max-width: 280px; color: #6b7280;">Wujudkan pakaian impianmu dengan layanan custom order terpercaya bersama tim profesional kami.</p>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        @foreach(['M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z', 'M21 2H3v1l9 9.26L3 21v1h18v-6.3L12 12l9-3.7V2z', 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z'] as $icon)
                            <a href="#" style="width: 36px; height: 36px; border-radius: 10px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; color: #6b7280; transition: all 0.2s; text-decoration: none;"
                                onmouseover="this.style.background='rgba(124,58,237,0.2)'; this.style.borderColor='rgba(124,58,237,0.4)'; this.style.color='#a78bfa'"
                                onmouseout="this.style.background='rgba(255,255,255,0.06)'; this.style.borderColor='rgba(255,255,255,0.08)'; this.style.color='#6b7280'">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 style="font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 16px;">Tautan</h4>
                    <ul style="display: flex; flex-direction: column; gap: 10px; list-style: none; padding: 0;">
                        @foreach(['Tentang Kami', 'Kebijakan Privasi', 'Syarat & Ketentuan', 'FAQ'] as $link)
                            <li><a href="#" style="font-size: 0.88rem; color: #6b7280; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b7280'">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 16px;">Hubungi Kami</h4>
                    <ul style="display: flex; flex-direction: column; gap: 10px; list-style: none; padding: 0;">
                        <li style="font-size: 0.88rem; color: #6b7280;">info@interco.com</li>
                        <li style="font-size: 0.88rem; color: #6b7280;">(021) 1234-5678</li>
                        <li style="font-size: 0.88rem; color: #6b7280;">Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 24px; display: flex; align-items: center; justify-content: space-between;">
                <p style="font-size: 0.8rem; color: #374151;">&copy; {{ date('Y') }} Interco. All rights reserved.</p>
                <p style="font-size: 0.8rem; color: #374151;">Made with ♥ in Indonesia</p>
            </div>
        </div>
    </footer>

    {{-- Intersection Observer Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Reveal on scroll
            const reveals = document.querySelectorAll('.reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
            reveals.forEach(el => observer.observe(el));

            // Manual smooth scroll using requestAnimationFrame
            function smoothScrollTo(targetY, duration) {
                const startY = window.pageYOffset;
                const diff = targetY - startY;
                let startTime = null;

                function step(currentTime) {
                    if (!startTime) startTime = currentTime;
                    const progress = Math.min((currentTime - startTime) / duration, 1);
                    // easeInOutCubic
                    const ease = progress < 0.5
                        ? 4 * progress * progress * progress
                        : 1 - Math.pow(-2 * progress + 2, 3) / 2;
                    window.scrollTo(0, startY + diff * ease);
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                }
                requestAnimationFrame(step);
            }

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href === '#') {
                        e.preventDefault();
                        smoothScrollTo(0, 800);
                        return;
                    }
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        const targetY = target.getBoundingClientRect().top + window.pageYOffset - 70;
                        smoothScrollTo(targetY, 800);
                    }
                });
            });
        });
    </script>

</body>
</html>
