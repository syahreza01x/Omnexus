<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', productModalOpen: false, cartOpen: false, mobileMenuOpen: false, selectedProduct: null, navScrolled: false, searchQuery: '', customOrderOpen: false, modalQty: 1 }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); window.addEventListener('scroll', () => { let sc = window.scrollY > 20; if(navScrolled !== sc) navScrolled = sc; }, { passive: true })" :class="{ 'dark': darkMode }" class="overflow-x-hidden" style="scroll-behavior: smooth;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.initialCartItems = {!! \Illuminate\Support\Js::from(collect($cart ?? [])->map(function($i) use ($cartProducts) {
            $p = isset($i['product_id']) ? $cartProducts->get((int)$i['product_id']) : null;
            return $p ? ['id' => $p->id, 'qty' => (int)$i['quantity'], 'price' => (float)$p->price] : null;
        })->filter()->values()) !!};
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interco — Wujudkan Pakaian Impianmu</title>
    <meta name="description" content="Custom order pakaian premium. Desain bebas, bahan berkualitas, pengerjaan cepat.">

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='1.5' numOctaves='1' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.05'/%3E%3C/svg%3E");
            opacity: 0.3;
            pointer-events: none;
        }
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            will-change: transform;
            transform: translateZ(0);
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

        /* ─── FAQ cards ─── */
        .faq-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .faq-card { background: var(--surface-2); }
        .faq-card.is-open {
            border-color: rgba(124,58,237,0.4);
            box-shadow: 0 12px 30px rgba(124,58,237,0.08);
        }
        .dark .faq-card.is-open {
            box-shadow: 0 12px 30px rgba(124,58,237,0.2);
        }
        
        .faq-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px;
            cursor: pointer;
            transition: all 0.3s;
            background: transparent;
        }
        .faq-header:hover {
            background: rgba(124,58,237,0.03);
        }
        .faq-header.is-open {
            background: rgba(124,58,237,0.04);
        }
        
        .faq-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            padding-right: 20px;
            transition: color 0.3s;
        }
        .faq-title.is-open {
            color: #7c3aed;
        }
        .dark .faq-title.is-open {
            color: #a78bfa;
        }
        
        .faq-icon {
            width: 36px; height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            background: rgba(124,58,237,0.08);
            color: #7c3aed;
        }
        .dark .faq-icon {
            background: rgba(124,58,237,0.15);
            color: #a78bfa;
        }
        .faq-icon.is-open {
            background: #7c3aed;
            color: white;
            transform: rotate(180deg);
        }

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
            overflow-y: auto;
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
            padding: 0 12px;
        }
        @media (min-width: 768px) {
            .nav-inner { padding: 0 24px; }
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

        /* ─── Mobile Hamburger Button ─── */
        .mobile-menu-btn {
            display: none;
            padding: 8px;
            border-radius: 10px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: var(--muted);
            transition: all 0.2s;
        }
        .mobile-menu-btn:hover {
            background: rgba(124,58,237,0.08);
        }

        /* ─── Mobile Nav Drawer ─── */
        .mobile-nav-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 150;
        }
        .mobile-nav-drawer {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: min(320px, 85vw);
            background: var(--surface);
            z-index: 151;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow-y: auto;
            box-shadow: -10px 0 40px rgba(0,0,0,0.15);
        }
        .dark .mobile-nav-drawer {
            background: #0f0f13;
        }
        .mobile-nav-drawer.open {
            transform: translateX(0);
        }
        .mobile-nav-backdrop.open {
            display: block;
        }
        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text);
            text-decoration: none;
            transition: background 0.2s;
            border-bottom: 1px solid var(--border);
        }
        .mobile-nav-link:hover {
            background: rgba(124,58,237,0.06);
        }
        .mobile-nav-link svg {
            width: 20px;
            height: 20px;
            color: var(--muted);
        }

        /* ─── Responsive: Hero grid ─── */
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
        }
        .hero-visual-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            padding: 20px;
        }
        .hero-stats-row {
            display: flex;
            gap: 32px;
            margin-top: 56px;
            padding-top: 32px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        /* ─── Responsive: Category grid ─── */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }
        /* ─── Responsive: Product grid ─── */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        /* ─── Responsive: Feature grid ─── */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        /* ─── Responsive: Review grid ─── */
        .review-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        /* ─── Responsive: Footer grid ─── */
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        /* ─── Responsive: Modal grid ─── */
        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 500px;
        }
        /* ─── Responsive: Form grid ─── */
        .form-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        /* ─── Responsive: Custom order grid ─── */
        .custom-order-form-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
            margin-bottom: 24px;
        }
        /* ─── Responsive: Product section header ─── */
        .section-header-flex {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 48px;
        }

        /* ═══════ MEDIA QUERIES ═══════ */

        /* Tablet & below (max 1024px) */
        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .hero-visual-grid {
                max-width: 480px;
                margin: 0 auto;
            }
            .category-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Mobile & below (max 768px) */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .nav-desktop-only {
                display: none !important;
            }

            .hero-grid {
                gap: 32px;
            }
            .hero-title {
                font-size: clamp(2rem, 8vw, 2.8rem) !important;
            }
            .hero-desc {
                font-size: 0.95rem;
            }
            .hero-stats-row {
                gap: 20px;
                margin-top: 36px;
                padding-top: 24px;
                flex-wrap: wrap;
            }
            .hero-stats-row > div:not(:empty) > div:first-child {
                font-size: 1.4rem !important;
            }
            .hero-visual-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                padding: 12px;
            }
            .category-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }
            .feature-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .feature-card {
                padding: 24px;
            }
            .review-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
            .review-card {
                padding: 20px;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }
            .footer-bottom {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
            .modal-grid {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            .modal-grid > div:first-child {
                max-height: 240px;
                border-radius: 28px 28px 0 0 !important;
            }
            .modal-content {
                border-radius: 24px;
                max-height: 90vh;
            }
            .modal-grid > div:last-child {
                padding: 24px 20px !important;
                max-height: none !important;
            }
            .form-grid-2col {
                grid-template-columns: 1fr;
            }
            .custom-order-form-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .section-header-flex {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .btn-primary {
                padding: 12px 22px;
                font-size: 0.85rem;
            }
            .btn-ghost {
                padding: 12px 22px;
                font-size: 0.85rem;
            }
            .section-title {
                font-size: clamp(1.4rem, 5vw, 1.8rem);
            }
        }

        /* Small mobile (max 480px) */
        @media (max-width: 480px) {
            .hero-visual-grid {
                grid-template-columns: 1fr;
                gap: 10px;
                padding: 8px;
            }
            .hero-visual-grid .hero-card:nth-child(2) {
                margin-top: 0 !important;
            }
            .hero-visual-grid .hero-card:last-child {
                grid-column: span 1 !important;
            }
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .product-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
            .hero-stats-row {
                gap: 16px;
            }
            .hero-stats-row .stat-divider {
                display: none;
            }
            .nav-inner {
                padding: 0 16px;
            }
            .cart-item {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    {{-- ─── Navbar ─── --}}
    <nav class="navbar" :class="{ 'scrolled': navScrolled }" id="navbar">
        <div class="nav-inner">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2" style="text-decoration: none;">
                <img src="{{ asset('images/logo.png') }}" alt="Interco Logo" class="w-8 h-8 md:w-9 md:h-9 object-contain" onerror="this.src='{{ asset('images/icon.png') }}'">
                <span class="font-bold text-xl md:text-2xl tracking-tight transition-colors" :style="(navScrolled || !darkMode) ? 'color: var(--text)' : 'color: white'">Interco</span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex flex-1 justify-center items-center gap-8">
                <a href="#" class="text-sm font-semibold transition-all duration-300" :class="(navScrolled || !darkMode) ? 'text-gray-800 hover:text-violet-600 dark:text-white dark:hover:text-violet-400' : 'text-white/85 hover:text-white'">Beranda</a>
                <a href="#produk" class="text-sm font-semibold transition-all duration-300" :class="(navScrolled || !darkMode) ? 'text-gray-800 hover:text-violet-600 dark:text-white dark:hover:text-violet-400' : 'text-white/85 hover:text-white'">Produk</a>
                <a href="#kategori" class="text-sm font-semibold transition-all duration-300" :class="(navScrolled || !darkMode) ? 'text-gray-800 hover:text-violet-600 dark:text-white dark:hover:text-violet-400' : 'text-white/85 hover:text-white'">Kategori</a>
                <a href="#testimoni" class="text-sm font-semibold transition-all duration-300" :class="(navScrolled || !darkMode) ? 'text-gray-800 hover:text-violet-600 dark:text-white dark:hover:text-violet-400' : 'text-white/85 hover:text-white'">Testimoni</a>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-1 md:gap-2">
                {{-- Compact Search --}}
                <div class="hidden sm:block relative w-48 search-wrap mr-1">
                    <input type="text" placeholder="Cari..." x-model="searchQuery"
                        class="w-full rounded-full py-1.5 pl-8 pr-3 text-sm focus:outline-none transition-all duration-300"
                        style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);"
                        :style="(navScrolled || !darkMode) ? 'background: var(--surface-2); border-color: var(--border); color: var(--text)' : 'background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.12); color: rgba(255,255,255,0.9)'">
                    <svg class="absolute left-2.5 top-2 h-4 w-4" style="color: rgba(255,255,255,0.4);" :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.4)'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                {{-- Dark toggle --}}
                <button @click="darkMode = !darkMode"
                    class="p-2 md:p-2.5 rounded-xl transition-all duration-200"
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
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50 text-gray-500 transition-colors hover:bg-gray-100 dark:bg-zinc-900 dark:text-gray-400 dark:hover:bg-zinc-800 dark:hover:text-gray-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <div id="cart-badge">
                        @if($cartCount > 0)
                            <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-violet-600 px-1 text-[9px] font-bold text-white ring-2 ring-white dark:ring-zinc-950">{{ $cartCount }}</span>
                        @endif
                    </div>
                </button>
                @endauth

                @auth
                    <div x-data="{ open: false }" class="relative hidden md:block nav-desktop-only">
                        <button @click="open = !open"
                            class="flex items-center justify-center p-2 md:p-2.5 rounded-xl transition-all duration-200"
                            style="color: rgba(255,255,255,0.7);"
                            :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.7)'"
                            :class="(navScrolled || !darkMode) ? 'hover:bg-gray-100 dark:hover:bg-zinc-800' : 'hover:bg-white/10'">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
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
                            <a href="{{ route('custom-orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors" style="color: var(--muted);" onmouseover="this.style.background='rgba(124,58,237,0.08)'" onmouseout="this.style.background='transparent'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                Custom Order Saya
                            </a>
                            <a href="{{ route('chat.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors" style="color: var(--muted);" onmouseover="this.style.background='rgba(124,58,237,0.08)'" onmouseout="this.style.background='transparent'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Chat Support
                            </a>
                        Masuk
                    </a>
                @endauth

                {{-- Hamburger Menu (Mobile) --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 md:p-2.5 rounded-xl transition-colors"
                        style="color: rgba(255,255,255,0.7);"
                        :style="(navScrolled || !darkMode) ? 'color: var(--muted)' : 'color: rgba(255,255,255,0.7)'"
                        :class="(navScrolled || !darkMode) ? 'hover:bg-gray-100 dark:hover:bg-zinc-800' : 'hover:bg-white/10'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Dropdown --}}
        <div x-show="mobileMenuOpen" x-transition.opacity class="md:hidden border-t" style="border-color: var(--border); background: var(--surface); position: absolute; width: 100%; top: 100%; left: 0;">
            <div class="flex flex-col py-4 px-6 space-y-4">
                <a href="#" @click="mobileMenuOpen = false" class="text-sm font-semibold" style="color: var(--text);">Beranda</a>
                <a href="#produk" @click="mobileMenuOpen = false" class="text-sm font-semibold" style="color: var(--text);">Produk</a>
                <a href="#kategori" @click="mobileMenuOpen = false" class="text-sm font-semibold" style="color: var(--text);">Kategori</a>
                <a href="#testimoni" @click="mobileMenuOpen = false" class="text-sm font-semibold" style="color: var(--text);">Testimoni</a>
            </div>
        </div>
    </nav>

    {{-- ─── Mobile Navigation Drawer ─── --}}
    <div id="mobileNavBackdrop" class="mobile-nav-backdrop" onclick="document.getElementById('mobileNavDrawer').classList.remove('open'); this.classList.remove('open');"></div>
    <div id="mobileNavDrawer" class="mobile-nav-drawer">
        <div style="padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('images/icon.png') }}" alt="Interco" style="height: 28px; width: 28px; object-fit: contain;">
                <span style="font-size: 1rem; font-weight: 800; color: var(--text);">Interco</span>
            </div>
            <button onclick="document.getElementById('mobileNavDrawer').classList.remove('open'); document.getElementById('mobileNavBackdrop').classList.remove('open');" style="padding: 8px; border-radius: 10px; border: 1px solid var(--border); background: transparent; cursor: pointer; color: var(--muted); display: flex; align-items: center; justify-content: center;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @auth
        <div style="padding: 16px 24px; background: var(--surface-2); border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 12px; background: #7c3aed; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1rem; flex-shrink: 0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="min-width: 0;">
                <p style="font-size: 0.9rem; font-weight: 700; color: var(--text); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name }}</p>
                <p style="font-size: 0.78rem; color: var(--muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->email }}</p>
            </div>
        </div>
        @endauth

        <nav>
            <a href="#" class="mobile-nav-link" onclick="document.getElementById('mobileNavDrawer').classList.remove('open'); document.getElementById('mobileNavBackdrop').classList.remove('open');">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Beranda
            </a>
            <a href="#kategori" class="mobile-nav-link" onclick="document.getElementById('mobileNavDrawer').classList.remove('open'); document.getElementById('mobileNavBackdrop').classList.remove('open');">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                Kategori
            </a>
            <a href="#produk" class="mobile-nav-link" onclick="document.getElementById('mobileNavDrawer').classList.remove('open'); document.getElementById('mobileNavBackdrop').classList.remove('open');">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                Produk
            </a>
            <a href="#testimoni" class="mobile-nav-link" onclick="document.getElementById('mobileNavDrawer').classList.remove('open'); document.getElementById('mobileNavBackdrop').classList.remove('open');">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                Testimoni
            </a>
        </nav>

        @auth
        <div style="border-top: 1px solid var(--border); margin-top: 4px;">
            <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
            </a>
            <a href="{{ route('orders.index') }}" class="mobile-nav-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                Riwayat Pesanan
            </a>
            <a href="{{ route('custom-orders.index') }}" class="mobile-nav-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Pesanan Custom
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-nav-link" style="width: 100%; background: none; border: none; cursor: pointer; font-family: inherit; color: #ef4444;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #ef4444;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
        @else
        <div style="padding: 20px 24px; border-top: 1px solid var(--border); margin-top: 4px;">
            <a href="{{ route('login') }}" class="btn-primary" style="width: 100%; justify-content: center; text-decoration: none; padding: 12px 20px;">
                Masuk
            </a>
        </div>
        @endauth
    </div>

    {{-- ─── Hero Section ─── --}}
    <section class="hero-section">
        <div class="hero-grain"></div>
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>

        <div style="max-width: 1280px; margin: 0 auto; padding: 80px 24px; width: 100%; position: relative; z-index: 1;">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
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
                        <a href="#" @click.prevent="customOrderOpen = true" class="btn-ghost">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                            </svg>
                            Custom Order
                        </a>
                    </div>

                    {{-- Mini stats --}}
                    <div class="flex flex-wrap gap-8 mt-14 pt-8" style="border-top: 1px solid rgba(255,255,255,0.08);">
                        <div>
                            <div style="font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #a78bfa, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">500+</div>
                            <div style="font-size: 0.78rem; color: #6b7280; margin-top: 4px; font-weight: 500;">Produk Custom</div>
                        </div>
                        <div class="stat-divider" style="width: 1px; background: rgba(255,255,255,0.08);"></div>
                        <div>
                            <div style="font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #a78bfa, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">2K+</div>
                            <div style="font-size: 0.78rem; color: #6b7280; margin-top: 4px; font-weight: 500;">Pelanggan Puas</div>
                        </div>
                        <div class="stat-divider" style="width: 1px; background: rgba(255,255,255,0.08);"></div>
                        <div>
                            <div style="font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #a78bfa, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">4.9</div>
                            <div style="font-size: 0.78rem; color: #6b7280; margin-top: 4px; font-weight: 500;">Rating Bintang</div>
                        </div>
                    </div>
                </div>

                {{-- Right: Floating cards --}}
                <div class="hero-visual grid grid-cols-1 md:grid-cols-2 gap-4 p-5">
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
                    <div class="hero-card ticker md:col-span-2" style="display: flex; flex-direction: column; gap: 16px;">
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
                                <div style="width: 100%; height: 100%; background: linear-gradient(90deg, #7c3aed, #a78bfa); border-radius: 99px;"></div>
                            </div>
                            <span style="font-size: 0.72rem; color: #a78bfa; font-weight: 700;">100% Sesuai</span>
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
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 reveal reveal-delay-1">
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
            <div class="reveal flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @forelse($products as $i => $product)
                    @php
                        $productData = \Illuminate\Support\Js::from([
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'price' => (int) $product->price,
                            'price_formatted' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                            'image' => asset($product->image_path ? 'storage/' . $product->image_path : 'images/items/1.png'),
                            'category' => $product->category,
                            'specifications' => $product->specifications,
                            'stock' => $product->stock,
                            'unit' => $product->unit,
                        ]);
                    @endphp
                    <div class="product-card reveal reveal-delay-{{ min($i + 1, 4) }}"
                        @click="cartOpen = false; selectedProduct = {{ $productData }}; modalQty = 1; productModalOpen = true;">
                        <div class="product-img-wrap">
                            @if($product->category)
                                <div class="product-category-tag z-10">{{ $product->category }}</div>
                            @endif
                            <div class="absolute inset-0 skeleton z-0" x-show="!loaded"></div>
                            <img src="{{ asset($product->image_path ? 'storage/' . $product->image_path : 'images/items/1.png') }}"
                                alt="{{ $product->name }}"
                                @load="loaded = true"
                                :class="loaded ? 'opacity-100' : 'opacity-0'"
                                class="relative z-0 transition-opacity duration-500"
                                loading="lazy">
                            <div class="product-overlay z-10">
                                <button class="product-overlay-btn" type="button">Lihat Detail</button>
                            </div>
                        </div>
                        <div style="padding: 16px 18px 18px;">
                            <h3 style="font-size: 0.9rem; font-weight: 700; color: var(--text); margin-bottom: 4px; line-height: 1.3;">{{ $product->name }}</h3>
                            <p style="font-size: 0.78rem; color: var(--muted); margin-bottom: 14px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $product->description }}</p>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-size: 1rem; font-weight: 800; color: var(--text);">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <button type="button"
                                    @click.stop="cartOpen = false; selectedProduct = {{ $productData }}; modalQty = 1; productModalOpen = true;"
                                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: rgba(124,58,237,0.1); color: #7c3aed; border: none; cursor: pointer; transition: all 0.3s;"
                                    onmouseover="this.style.background='#7c3aed'; this.style.color='white'; this.style.transform='scale(1.1)'"
                                    onmouseout="this.style.background='rgba(124,58,237,0.1)'; this.style.color='#7c3aed'; this.style.transform='scale(1)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                    </svg>
                                    Keranjang
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
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
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
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

    {{-- ─── FAQ Section ─── --}}
    <section id="faq" style="padding: 100px 0; background: var(--surface-2);">
        <div style="max-width: 800px; margin: 0 auto; padding: 0 24px;">
            <div class="reveal" style="text-align: center; margin-bottom: 56px;">
                <div class="section-label" style="margin: 0 auto 16px;">Bantuan</div>
                <h2 class="section-title" style="color: var(--text);">Pertanyaan Umum</h2>
                <p style="color: var(--muted); margin-top: 12px; font-size: 1rem;">Temukan jawaban atas pertanyaan yang sering diajukan</p>
            </div>

            <div class="reveal reveal-delay-1" x-data="{ openFaq: null }">
                @php
                    $faqs = [
                        [
                            'q' => 'Bagaimana cara melakukan custom order?',
                            'a' => 'Anda bisa melakukan custom order dengan mengklik tombol "Custom Order" di halaman utama, pilih kategori produk, upload desain Anda (opsional), tentukan jumlah pesanan, dan isi detail lainnya. Tim kami akan segera menghubungi Anda untuk konfirmasi.'
                        ],
                        [
                            'q' => 'Berapa lama waktu produksi pesanan custom?',
                            'a' => 'Waktu produksi standar adalah 3–7 hari kerja tergantung jenis dan jumlah pesanan. Untuk pesanan dalam jumlah besar, waktu produksi dapat lebih lama. Kami akan menginformasikan estimasi waktu yang akurat setelah pesanan dikonfirmasi.'
                        ],
                        [
                            'q' => 'Apakah ada minimum order untuk custom?',
                            'a' => 'Untuk custom order, minimum pemesanan adalah 1 pcs. Namun, untuk pesanan dalam jumlah besar (di atas 50 pcs), Anda akan mendapatkan harga spesial yang lebih terjangkau.'
                        ],
                        [
                            'q' => 'Metode pembayaran apa saja yang tersedia?',
                            'a' => 'Kami menerima pembayaran melalui transfer bank (BCA & Mandiri). Setelah pesanan dibuat, Anda akan mendapatkan instruksi pembayaran lengkap. Pembayaran akan diverifikasi oleh admin kami.'
                        ],
                        [
                            'q' => 'Bagaimana cara pengiriman pesanan?',
                            'a' => 'Kami menyediakan dua pilihan: jemput di tempat (pickup) atau dikirim ke alamat Anda. Pengiriman tersedia ke seluruh wilayah Indonesia melalui jasa ekspedisi terpercaya.'
                        ],
                        [
                            'q' => 'Apakah bisa revisi desain setelah order?',
                            'a' => 'Ya, Anda bisa melakukan revisi desain sebelum proses produksi dimulai. Tim kami akan mengirimkan mockup desain terlebih dahulu untuk persetujuan Anda. Revisi gratis hingga 2 kali.'
                        ],
                    ];
                @endphp

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($faqs as $i => $faq)
                        <div class="faq-card" :class="{ 'is-open': openFaq === {{ $i }} }">
                            
                            {{-- Clickable Header --}}
                            <div class="faq-header" :class="{ 'is-open': openFaq === {{ $i }} }" @click="openFaq = openFaq === {{ $i }} ? null : {{ $i }}">
                                
                                <span class="faq-title" :class="{ 'is-open': openFaq === {{ $i }} }">{{ $faq['q'] }}</span>
                                
                                <div class="faq-icon" :class="{ 'is-open': openFaq === {{ $i }} }">
                                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </div>
                            </div>

                            {{-- Answer Content --}}
                            <div x-show="openFaq === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 style="display: none;">
                                <div style="padding: 0 24px 28px;">
                                    <div style="height: 1px; background: var(--border); margin-bottom: 20px; opacity: 0.5;"></div>
                                    <p style="font-size: 0.95rem; color: var(--muted); line-height: 1.8; margin: 0;">{{ $faq['a'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
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

        <div class="modal-content" style="overflow-y: auto;" x-data="{ modalQty: 1 }" x-init="$watch('productModalOpen', value => { if(value) modalQty = 1 })">
            {{-- Close btn --}}
            <button type="button" @click="productModalOpen = false"
                style="position: absolute; top: 16px; right: 16px; z-index: 10; width: 36px; height: 36px; border-radius: 10px; background: var(--surface-2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; color: var(--muted);"
                onmouseover="this.style.background='var(--border)'"
                onmouseout="this.style.background='var(--surface-2)'">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2 min-h-[500px]">
                {{-- Product image --}}
                <div class="overflow-hidden rounded-t-[28px] md:rounded-tr-none md:rounded-l-[28px] h-64 md:h-auto">
                    <img x-bind:src="selectedProduct?.image" x-bind:alt="selectedProduct?.name"
                        style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;" class="modal-img">
                </div>

                {{-- Product info --}}
                <div style="padding: 40px 36px;" class="overflow-y-auto max-h-[60vh] md:max-h-[80vh]">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <span style="padding: 4px 12px; border-radius: 99px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; background: rgba(124,58,237,0.1); color: #7c3aed; border: 1px solid rgba(124,58,237,0.2);"
                            x-text="selectedProduct?.category || 'Produk'"></span>
                        <span style="font-size: 0.78rem; color: var(--muted);" x-text="selectedProduct ? selectedProduct.stock + ' stok tersedia' : ''"></span>
                    </div>

                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: var(--text); line-height: 1.2; margin-bottom: 12px;" x-text="selectedProduct?.name"></h3>
                    <p style="font-size: 0.9rem; line-height: 1.75; color: var(--muted); margin-bottom: 24px;" x-text="selectedProduct?.description"></p>

                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div style="padding: 16px; background: var(--surface-2); border-radius: 14px; border: 1px solid var(--border);">
                            <div style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); margin-bottom: 6px;">Harga</div>
                            <div style="font-size: 1.2rem; font-weight: 800; color: var(--text);" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format((selectedProduct?.price || 0) * modalQty)"></div>
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
                        <div class="flex flex-col gap-3 w-full">
                            <div class="flex gap-3 w-full items-end">
                                <div>
                                    <label style="display: block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Qty</label>
                                    <input type="number" name="quantity" min="1" x-model.number="modalQty"
                                        style="width: 90px; padding: 12px 14px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 12px; color: var(--text); font-size: 0.9rem; font-weight: 600; outline: none; transition: border-color 0.2s;"
                                        onfocus="this.style.borderColor='#7c3aed'"
                                        onblur="this.style.borderColor='var(--border)'">
                                </div>
                                <button type="button" @click="productModalOpen = false; window.dispatchEvent(new CustomEvent('open-chat', { detail: selectedProduct }))" class="btn-primary" style="flex: 1; justify-content: center; background: rgba(124,58,237,0.1); color: #7c3aed; border: 1px solid rgba(124,58,237,0.2); white-space: nowrap; height: 48px; display: flex; align-items: center; gap: 6px; padding: 0 16px;">
                                    <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                                    </svg>
                                    <span class="text-[0.8rem] sm:text-[0.85rem]">Hubungi Penjual</span>
                                </button>
                            </div>
                            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; white-space: nowrap; height: 48px; display: flex; align-items: center; gap: 6px;">
                                <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                </svg>
                                + Keranjang
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
                            <p style="font-size: 0.83rem; color: var(--muted); line-height: 1.5;">Kamu perlu <strong style="color: var(--text);">masuk</strong> terlebih dahulu untuk memesan.</p>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <button type="button" @click="productModalOpen = false; window.dispatchEvent(new CustomEvent('open-chat', { detail: selectedProduct }))" class="btn-primary" style="width: 100%; justify-content: center; background: rgba(124,58,237,0.1); color: #7c3aed; border: 1px solid rgba(124,58,237,0.2); white-space: nowrap; height: 48px; display: flex; align-items: center; gap: 6px;">
                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                                </svg>
                                Hubungi Penjual
                            </button>
                            <a href="{{ route('login') }}" class="btn-primary" style="width: 100%; justify-content: center; text-align: center; text-decoration: none; white-space: nowrap; height: 48px; display: flex; align-items: center; gap: 6px;">
                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                </svg>
                                Masuk
                            </a>
                        </div>
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
            style="width: min(500px, 100vw); background: var(--surface); box-shadow: -20px 0 60px rgba(0,0,0,0.15);"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="transform translate-x-full"
            x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform translate-x-0"
            x-transition:leave-end="transform translate-x-full">

            {{-- Header --}}
            <div id="cart-drawer-inner" class="flex flex-col h-full">
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
                                    <img src="{{ asset($cartProduct->image_path ? 'storage/' . $cartProduct->image_path : 'images/items/1.png') }}"
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
                                                style="width: 60px; padding: 6px 10px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.82rem; font-weight: 600; outline: none; text-align: center;"
                                                onchange="updateCartAJAX(this.form)">
                                        </form>
                                        <form method="POST" action="{{ route('cart.remove', $cartProduct) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="padding: 6px 12px; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                                                onmouseover="this.style.background='#ef4444'; this.style.color='white'"
                                                onmouseout="this.style.background='rgba(239,68,68,0.1)'; this.style.color='#ef4444'"
                                                onclick="event.preventDefault(); updateCartAJAX(this.form)">Hapus</button>
                                        </form>
                                        <button type="button" @click="cartOpen = false; selectedProduct = {{ json_encode($cartProduct) }}; window.dispatchEvent(new CustomEvent('open-chat', { detail: selectedProduct }))" style="padding: 6px 12px; background: rgba(124,58,237,0.1); color: #7c3aed; border: 1px solid rgba(124,58,237,0.2); border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 4px;" onmouseover="this.style.background='rgba(124,58,237,0.2)'" onmouseout="this.style.background='rgba(124,58,237,0.1)'">
                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                                            Chat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div style="text-align: center; padding: 64px 24px; background: rgba(124,58,237,0.02); border: 2px dashed var(--border); border-radius: 20px;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: rgba(124,58,237,0.08); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 36px; height: 36px; color: #7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                        </div>
                        <h4 style="font-size: 1.1rem; color: var(--text); font-weight: 700; margin-bottom: 8px;">Keranjang Kosong</h4>
                        <p style="font-size: 0.85rem; color: var(--muted); line-height: 1.6; margin-bottom: 24px;">Kamu belum menambahkan apapun. Yuk temukan produk custom impianmu sekarang!</p>
                        <button type="button" @click="cartOpen = false" class="btn-primary" style="padding: 10px 24px; font-size: 0.85rem; border-radius: 99px;">
                            Mulai Belanja
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- Subtotal + actions --}}
            @if($cartCount > 0)
            <div style="padding: 20px; border-top: 1px solid var(--border); position: sticky; bottom: 0; background: var(--surface);">
                <div style="background: var(--surface-2); border: 1px solid var(--border); border-radius: 16px; padding: 16px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 0.85rem; color: var(--muted);">Subtotal</span>
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--text);" x-text="formatRp(cartSubtotal)"></span>
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
    </div>

    {{-- ─── Custom Order Modal ─── --}}
    <div x-cloak x-show="customOrderOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="absolute inset-0" style="background: rgba(0,0,0,0.75); backdrop-filter: blur(8px);" @click="customOrderOpen = false"></div>

        <div class="relative w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl" style="background: var(--surface); border: 1px solid var(--border); max-height: 90vh; display: flex; flex-direction: column;">
            <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text);">Pesan Custom Order</h3>
                <button type="button" @click="customOrderOpen = false" style="color: var(--muted); hover:text-white;">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div style="padding: 24px; overflow-y: auto;">
                @auth
                <form method="POST" action="{{ route('custom-orders.store') }}" enctype="multipart/form-data" x-data="{ 
                    selectedProductName: '',
                    selectedCategory: '',
                    selectedMaterial: '',
                    get dynamicCategories() {
                        if (!this.selectedProductName) return [];
                        const name = this.selectedProductName.toLowerCase();
                        if (name.includes('kaos') || name.includes('t-shirt') || name.includes('baju')) {
                            return [
                                {name: 'Lengan Pendek', img: '{{ asset('images/items/3.png') }}'},
                                {name: 'Lengan Panjang', img: '{{ asset('images/items/4.png') }}'},
                                {name: 'Oversize', img: '{{ asset('images/items/5.png') }}'},
                                {name: 'V-Neck', img: '{{ asset('images/items/6.png') }}'}
                            ];
                        }
                        if (name.includes('hoodie') || name.includes('jaket') || name.includes('sweater') || name.includes('outer')) {
                            return [
                                {name: 'Pullover (Tanpa Resleting)', img: '{{ asset('images/items/1.png') }}'},
                                {name: 'Zipper (Resleting)', img: '{{ asset('images/items/2.png') }}'},
                                {name: 'Crop', img: '{{ asset('images/items/3.png') }}'}
                            ];
                        }
                        if (name.includes('totebag') || name.includes('tas')) {
                            return [
                                {name: 'Pakai Resleting', img: '{{ asset('images/items/1.png') }}'},
                                {name: 'Pakai Perekat (Velcro)', img: '{{ asset('images/items/2.png') }}'},
                                {name: 'Tanpa Penutup', img: '{{ asset('images/items/3.png') }}'}
                            ];
                        }
                        if (name.includes('pin') || name.includes('gantungan')) {
                            return [
                                {name: 'Glossy', img: '{{ asset('images/items/1.png') }}'},
                                {name: 'Doff / Matte', img: '{{ asset('images/items/2.png') }}'},
                                {name: 'Hologram', img: '{{ asset('images/items/3.png') }}'}
                            ];
                        }
                        return [
                            {name: 'Standar', img: '{{ asset('images/items/1.png') }}'},
                            {name: 'Premium', img: '{{ asset('images/items/2.png') }}'}
                        ];
                    },
                    get dynamicMaterials() {
                        if (!this.selectedProductName) return [];
                        const name = this.selectedProductName.toLowerCase();
                        if (name.includes('kaos') || name.includes('t-shirt') || name.includes('baju')) {
                            return ['Cotton Combed 30s', 'Cotton Combed 24s', 'Cotton Bamboo', 'Polyester'];
                        }
                        if (name.includes('hoodie') || name.includes('jaket') || name.includes('sweater') || name.includes('outer')) {
                            return ['Fleece', 'Baby Terry', 'Cotton Dorr'];
                        }
                        if (name.includes('totebag') || name.includes('tas')) {
                            return ['Kanvas', 'Blacu', 'Drill'];
                        }
                        if (name.includes('pin') || name.includes('gantungan')) {
                            return ['Plastik PVC', 'Akrilik', 'Logam/Kaleng'];
                        }
                        return ['Bahan Standar', 'Bahan Premium'];
                    }
                }">
                    @csrf
                    
                    <div style="margin-bottom: 24px;">
                        <label class="form-label mb-3 block">Basis Pakaian (Benda Kosongan) <span style="color: #ef4444">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($allProducts as $p)
                                <label class="cursor-pointer" @click="selectedProductName = '{{ addslashes($p->name) }}'; selectedCategory = ''; selectedMaterial = '';">
                                    <input type="radio" name="product_id" value="{{ $p->id }}" class="peer sr-only" required>
                                    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 peer-checked:border-violet-500 peer-checked:ring-2 peer-checked:ring-violet-500 overflow-hidden transition-all hover:border-violet-300 bg-white dark:bg-zinc-800 h-full flex flex-col">
                                        <img src="{{ asset($p->image_path ?: 'images/items/1.png') }}" class="w-full h-24 object-cover">
                                        <div class="p-3 bg-white dark:bg-zinc-800">
                                            <h4 class="font-bold text-xs text-gray-900 dark:text-white line-clamp-2 leading-tight">{{ $p->name }}</h4>
                                            <p class="text-[10px] text-gray-500 mt-1 font-medium">Stok: {{ $p->stock }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;" x-show="selectedProductName" x-transition style="display: none;">
                        <label class="form-label mb-3 block">Kategori / Varian Tambahan (Opsional)</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <template x-for="(cat, index) in dynamicCategories" :key="index">
                                <label class="cursor-pointer">
                                    <input type="radio" name="category" :value="cat.name" x-model="selectedCategory" class="peer sr-only">
                                    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 peer-checked:border-violet-500 peer-checked:bg-violet-50 dark:peer-checked:bg-violet-900/20 overflow-hidden transition-all hover:border-violet-300 bg-white dark:bg-zinc-800 flex items-center gap-3 p-2">
                                        <img :src="cat.img" onerror="this.src='{{ asset('images/items/1.png') }}'" class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                                        <span class="font-semibold text-xs text-gray-800 dark:text-gray-200" x-text="cat.name"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;" x-show="selectedProductName" x-transition style="display: none;">
                        <label class="form-label mb-3 block">Pilih Bahan (Opsional)</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(mat, index) in dynamicMaterials" :key="index">
                                <label class="cursor-pointer">
                                    <input type="radio" name="material" :value="mat" x-model="selectedMaterial" class="peer sr-only">
                                    <div class="px-4 py-2 rounded-full border border-gray-200 dark:border-zinc-700 peer-checked:border-violet-500 peer-checked:bg-violet-600 peer-checked:text-white text-xs font-bold text-gray-600 dark:text-gray-300 transition-all hover:border-violet-400" x-text="mat">
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="custom-order-form-grid">
                        <div>
                            <label class="form-label mb-2 block">Jumlah <span style="color: #ef4444">*</span></label>
                            <input type="number" name="quantity" min="1" value="1" required class="form-input text-lg font-bold">
                        </div>
                        <div x-data="{ fileName: '', preview: null }">
                            <label class="form-label mb-2 block">Upload Desain / Logo (Max 5MB)</label>
                            <div class="relative flex flex-col items-center justify-center w-full h-24 border-2 border-dashed rounded-xl transition-colors hover:bg-gray-50 dark:hover:bg-zinc-800/50" :class="preview ? 'border-violet-500 bg-violet-50/50 dark:bg-violet-900/10' : 'border-gray-300 dark:border-zinc-700'">
                                <div class="w-full h-full cursor-pointer flex flex-col items-center justify-center pt-3 pb-3" x-show="!preview" @click="$refs.fileInput.click()">
                                    <svg class="w-6 h-6 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><span class="font-semibold text-violet-600">Klik</span> atau Drop gambar</p>
                                </div>
                                <div class="w-full h-full flex items-center gap-3 p-3" x-show="preview" style="display: none;">
                                    <img :src="preview" class="h-full rounded-lg object-contain w-16 bg-white cursor-pointer" @click="$refs.fileInput.click()">
                                    <div class="flex-1 min-w-0 cursor-pointer" @click="$refs.fileInput.click()">
                                        <p class="text-xs font-bold text-gray-900 dark:text-white truncate" x-text="fileName"></p>
                                        <p class="text-[10px] text-green-600 font-medium">Siap diupload (Klik untuk ganti)</p>
                                    </div>
                                    <button type="button" @click.stop="preview = null; fileName = ''; $refs.fileInput.value = ''" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg relative z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                                <input type="file" name="design_file" accept="image/*" class="sr-only" x-ref="fileInput" 
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
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label class="form-label mb-2 block">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3" placeholder="Sertakan detail ukuran, posisi logo, warna sablon, dll..." class="form-input"></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary" style="background: #7c3aed; padding: 12px 24px; font-size: 0.95rem;">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Permintaan Custom
                        </button>
                    </div>
                </form>
                @else
                <div style="text-align: center; padding: 40px 0;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 16px; color: #7c3aed;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 8px;">Anda Belum Masuk</h3>
                    <p style="color: var(--muted); margin-bottom: 24px;">Silakan login terlebih dahulu untuk melakukan pemesanan custom order.</p>
                    <a href="{{ route('login') }}" class="btn-primary" style="display: inline-flex;">Masuk Sekarang</a>
                </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- ─── Footer ─── --}}
    <footer class="footer">
        <div style="max-width: 1280px; margin: 0 auto; padding: 64px 24px 32px;">
            <div class="footer-grid">
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
            <div class="footer-bottom">
                <p style="font-size: 0.8rem; color: #374151;">&copy; {{ date('Y') }} Interco. All rights reserved.</p>
                <p style="font-size: 0.8rem; color: #374151;">Made with ♥ in Indonesia</p>
            </div>
        </div>
    </footer>

    {{-- ─── Chatbot FAQ Widget ─── --}}
    <div x-data="chatbot({{ auth()->check() ? 'true' : 'false' }})" class="fixed inset-0 z-[900] pointer-events-none font-sans flex items-end justify-end">
        {{-- Overlay Backdrop --}}
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/40 backdrop-blur-sm pointer-events-auto" @click="open = false"></div>

        {{-- Chat Window --}}
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative mb-20 mr-4 w-[92vw] sm:w-[400px] h-[75vh] max-h-[680px] bg-white dark:bg-zinc-900 shadow-2xl rounded-3xl overflow-hidden flex flex-col pointer-events-auto"
             style="box-shadow: 0 25px 60px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.05);">

            {{-- Header - Warm & Humanistic --}}
            <div style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 50%, #5b21b6 100%); padding: 16px 16px 20px; position: relative; overflow: hidden;">
                {{-- Decorative circles --}}
                <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.06);"></div>
                <div style="position: absolute; bottom: -30px; right: 40px; width: 70px; height: 70px; border-radius: 50%; background: rgba(255,255,255,0.04);"></div>

                <div style="display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 1;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        {{-- Avatar with logo --}}
                        <div style="position: relative; flex-shrink: 0;">
                            <div style="width: 46px; height: 46px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.2); overflow: hidden;">
                                <img src="{{ asset('images/icon.png') }}" alt="CS" style="width: 28px; height: 28px; object-fit: contain;">
                            </div>
                            {{-- Online indicator --}}
                            <div style="position: absolute; bottom: 1px; right: 1px; width: 12px; height: 12px; background: #22c55e; border: 2px solid #6d28d9; border-radius: 50%;"></div>
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <h4 style="color: white; font-weight: 700; font-size: 0.95rem; line-height: 1.2;">CS Interco</h4>
                                <span style="background: rgba(255,255,255,0.2); color: white; font-size: 0.6rem; font-weight: 700; padding: 1px 6px; border-radius: 99px; letter-spacing: 0.05em;">AI</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px; margin-top: 2px;">
                                <div style="width: 6px; height: 6px; background: #4ade80; border-radius: 50%; animation: pulse 2s infinite;"></div>
                                <p style="color: rgba(255,255,255,0.8); font-size: 0.72rem;">Online sekarang · siap membantu 😊</p>
                            </div>
                        </div>
                    </div>
                    <button @click="open = false" style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; color: white;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Info bar --}}
                <div style="margin-top: 14px; background: rgba(255,255,255,0.1); border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; gap: 10px; position: relative; z-index: 1;">
                    <svg style="width: 16px; height: 16px; color: #fde68a; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                    <p style="color: rgba(255,255,255,0.9); font-size: 0.72rem; line-height: 1.4;">Biasanya membalas dalam hitungan detik. Ajukan pertanyaan apa saja seputar produk & layanan kami!</p>
                </div>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-zinc-950" id="chat-messages" style="padding: 16px; display: flex; flex-direction: column; gap: 14px; scroll-behavior: smooth;">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.type === 'bot' ? 'flex items-end gap-2' : 'flex items-end gap-2 flex-row-reverse'">
                        {{-- Bot avatar --}}
                        <div x-show="msg.type === 'bot'" style="width: 30px; height: 30px; border-radius: 50%; background: white; border: 2px solid #ede9fe; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-bottom: 4px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
                            <img src="{{ asset('images/icon.png') }}" style="width: 18px; height: 18px; object-fit: contain;">
                        </div>

                        <div :class="msg.type === 'bot' ? 'max-w-[78%]' : 'max-w-[78%]'" style="display: flex; flex-direction: column; gap: 4px;">
                            {{-- Sender name (bot only) --}}
                            <span x-show="msg.type === 'bot'" style="font-size: 0.65rem; font-weight: 700; color: #7c3aed; margin-left: 2px;">CS Interco</span>

                            {{-- Product card context (user bubble) --}}
                            <template x-if="msg.product">
                                <div :class="msg.type === 'user' ? 'bg-indigo-500' : 'bg-white dark:bg-zinc-800'" style="border-radius: 14px; padding: 8px; display: flex; gap: 10px; cursor: pointer; margin-bottom: 4px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); border: 1px solid rgba(124,58,237,0.15);" @click="selectedProduct = msg.product; productModalOpen = true">
                                    <img :src="msg.product.image" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; flex-shrink: 0;">
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-size: 0.75rem; font-weight: 700; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;" :style="msg.type === 'user' ? 'color:white' : 'color: var(--text)'" x-text="msg.product.name"></div>
                                        <div style="font-size: 0.68rem; font-weight: 600; margin-top: 1px;" :style="msg.type === 'user' ? 'color: rgba(255,255,255,0.8)' : 'color: #7c3aed'" x-text="msg.product.price_formatted"></div>
                                    </div>
                                </div>
                            </template>

                            {{-- Bubble --}}
                            <div :style="msg.type === 'bot'
                                ? 'background: white; color: #1f2937; border-radius: 4px 18px 18px 18px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); border: 1px solid #f3f4f6;'
                                : 'background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white; border-radius: 18px 4px 18px 18px; box-shadow: 0 2px 8px rgba(124,58,237,0.35);'"
                                style="padding: 10px 14px;" class="dark:border-gray-700">
                                <p x-html="msg.text" style="font-size: 0.84rem; line-height: 1.6; margin: 0;"></p>
                            </div>

                            {{-- Timestamp --}}
                            <span style="font-size: 0.62rem; color: #9ca3af; margin: 0 4px;" :style="msg.type === 'user' ? 'text-align: right;' : ''" x-text="msg.time"></span>
                        </div>
                    </div>
                </template>

                {{-- Typing indicator --}}
                <div x-show="isTyping" class="flex items-end gap-2">
                    <div style="width: 30px; height: 30px; border-radius: 50%; background: white; border: 2px solid #ede9fe; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                        <img src="{{ asset('images/icon.png') }}" style="width: 18px; height: 18px; object-fit: contain;">
                    </div>
                    <div style="background: white; border-radius: 4px 18px 18px 18px; padding: 12px 16px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); border: 1px solid #f3f4f6; display: flex; align-items: center; gap: 5px;">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #a78bfa; animation: bounce 1.2s infinite;" ></div>
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #a78bfa; animation: bounce 1.2s infinite 0.2s;"></div>
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #a78bfa; animation: bounce 1.2s infinite 0.4s;"></div>
                    </div>
                </div>

                {{-- Quick reply suggestions (only shown at start) --}}
                <div x-show="messages.length === 1 && !isTyping" class="flex flex-wrap gap-2 mt-2">
                    <p style="width: 100%; font-size: 0.7rem; color: #9ca3af; font-weight: 600; margin-bottom: 4px;">💡 Pertanyaan populer:</p>
                    <template x-for="q in quickReplies" :key="q">
                        <button @click="quickSend(q)" style="background: white; border: 1px solid #e9d5ff; color: #7c3aed; font-size: 0.75rem; font-weight: 600; padding: 6px 14px; border-radius: 99px; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);" onmouseover="this.style.background='#7c3aed'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='#7c3aed'" x-text="q"></button>
                    </template>
                </div>
            </div>

            {{-- Footer --}}
            <div style="padding: 12px 16px; background: white; border-top: 1px solid #f3f4f6; flex-shrink: 0;" class="dark:bg-zinc-900 dark:border-zinc-800">
                {{-- Product Context Preview --}}
                <div x-show="productContext" x-transition style="margin-bottom: 10px; background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 12px; padding: 8px 12px; display: flex; align-items: center; gap: 10px; position: relative;">
                    <button @click="productContext = null" style="position: absolute; top: -8px; right: -8px; width: 22px; height: 22px; background: white; border: 1px solid #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #6b7280; box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
                        <svg style="width: 11px; height: 11px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <img :src="productContext?.image" style="width: 36px; height: 36px; object-fit: cover; border-radius: 8px; flex-shrink: 0; border: 1px solid #e9d5ff;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.72rem; font-weight: 700; color: #1f2937; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;" x-text="productContext?.name"></div>
                        <div style="font-size: 0.68rem; color: #7c3aed; font-weight: 600;" x-text="productContext?.price_formatted"></div>
                    </div>
                    <svg style="width: 14px; height: 14px; color: #7c3aed; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </div>

                <form @submit.prevent="sendMessage" style="display: flex; gap: 8px; align-items: center;">
                    <div style="flex: 1; position: relative;">
                        <input id="chat-input" type="text" x-model="userInput" 
                               placeholder="Tulis pesanmu di sini..." 
                               style="width: 100%; background: #f9fafb; border: 1.5px solid #e5e7eb; color: #1f2937; font-size: 0.84rem; border-radius: 24px; padding: 10px 18px; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                               onfocus="this.style.borderColor='#7c3aed'; this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.1)'"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                               :disabled="isTyping"
                               class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-200 dark:placeholder-gray-500">
                    </div>
                    <button type="submit" 
                            :disabled="isTyping || (!productContext && userInput.trim() === '')"
                            style="width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #7c3aed, #6d28d9); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: all 0.2s; box-shadow: 0 4px 12px rgba(124,58,237,0.4);"
                            onmouseover="if(!this.disabled) { this.style.transform='scale(1.08)'; this.style.boxShadow='0 6px 16px rgba(124,58,237,0.5)'; }"
                            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(124,58,237,0.4)';">
                        <svg style="width: 18px; height: 18px; color: white; transform: rotate(45deg) translateX(-1px);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
                <p style="text-align: center; font-size: 0.63rem; color: #d1d5db; margin-top: 8px;">Powered by Gemini AI · Interco Customer Service</p>
            </div>
        </div>

        {{-- Floating Button --}}
        <button @click="open = !open"
                class="absolute bottom-5 right-5 pointer-events-auto focus:outline-none"
                style="width: 56px; height: 56px; border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 8px 25px rgba(124,58,237,0.45); background: linear-gradient(135deg, #7c3aed, #6d28d9);"
                onmouseover="this.style.transform='scale(1.1) translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(124,58,237,0.55)';"
                onmouseout="this.style.transform='scale(1) translateY(0)'; this.style.boxShadow='0 8px 25px rgba(124,58,237,0.45)';">
            <svg x-show="!open" style="width: 24px; height: 24px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <svg x-show="open" x-cloak style="width: 22px; height: 22px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span x-show="!open" class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white dark:border-gray-900 rounded-full animate-pulse"></span>
        </button>
    </div>

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

        document.addEventListener('alpine:init', () => {
            Alpine.data('chatbot', (isLoggedIn = false) => ({
                isLoggedIn: isLoggedIn,
                open: false,
                isTyping: false,
                userInput: '',
                productContext: null,
                quickReplies: [
                    '💰 Berapa harga minimum order?',
                    '📦 Berapa lama proses produksi?',
                    '🎨 Bisa custom desain sendiri?',
                    '🚚 Pengiriman ke luar kota?'
                ],
                messages: [
                    { type: 'bot', text: 'Halo Kak! 👋 Selamat datang di <strong>Interco</strong>! Saya CS yang siap membantu kamu seputar produk sablon & konveksi custom kami. Mau tanya apa, Kak? 😊', time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }
                ],

                getTime() {
                    return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                },

                init() {
                    this.$watch('messages', () => this.scrollToBottom());
                    this.$watch('isTyping', () => this.scrollToBottom());

                    window.addEventListener('open-chat', (e) => {
                        this.open = true;
                        this.productContext = e.detail;
                        this.$nextTick(() => {
                            const input = document.getElementById('chat-input');
                            if(input) input.focus();
                        });
                    });
                },

                quickSend(text) {
                    this.userInput = text;
                    this.sendMessage();
                },

                async sendMessage() {
                    if (!this.productContext && this.userInput.trim() === '') return;

                    if (!this.isLoggedIn) {
                        const question = this.userInput;
                        const contextProduct = this.productContext;

                        this.messages.push({
                            type: 'user',
                            text: question || "Halo kak, saya mau tanya tentang produk ini dong.",
                            product: contextProduct,
                            time: this.getTime()
                        });

                        this.userInput = '';
                        this.productContext = null;
                        this.isTyping = true;

                        setTimeout(() => {
                            this.isTyping = false;
                            this.messages.push({
                                type: 'bot',
                                text: 'Hei Kak! 😊 Senang kamu mau tanya-tanya di sini. Tapi supaya CS bisa bantu lebih maksimal, Kakak perlu <a href="/login" style="color:#a78bfa; font-weight:700; text-decoration:underline;">Masuk / Login</a> dulu ya. Gak lama kok, setelah login langsung bisa chat sepuasnya! 🔐',
                                time: this.getTime()
                            });
                        }, 900);
                        return;
                    }

                    const question = this.userInput;
                    const contextProduct = this.productContext;

                    this.userInput = '';
                    this.productContext = null;

                    let displayQuestion = question;
                    if (contextProduct && question.trim() === '') {
                        displayQuestion = "Halo kak, saya mau tanya tentang produk ini dong.";
                    }

                    this.messages.push({
                        type: 'user',
                        text: displayQuestion,
                        product: contextProduct,
                        time: this.getTime()
                    });

                    this.isTyping = true;

                    try {
                        let promptText = displayQuestion;
                        if (contextProduct) {
                            promptText = `[SISTEM: Pengguna melampirkan produk. Nama: ${contextProduct.name}, Harga: ${contextProduct.price_formatted}, Kategori: ${contextProduct.category}, Spesifikasi: ${contextProduct.specifications}, Sisa Stok: ${contextProduct.stock}. Berikan jawaban spesifik terkait produk ini]\n\nPesan User: ${displayQuestion}`;
                        }

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const response = await fetch('{{ route("chat.api") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: promptText })
                        });

                        const data = await response.json();

                        this.isTyping = false;
                        if (response.ok) {
                            this.messages.push({ type: 'bot', text: data.reply, time: this.getTime() });
                        } else {
                            this.messages.push({ type: 'bot', text: data.reply || 'Aduh Kak, sepertinya CS lagi ada gangguan jaringan sebentar. Coba tanya ulang ya! 🙏', time: this.getTime() });
                        }
                    } catch (error) {
                        this.isTyping = false;
                        this.messages.push({ type: 'bot', text: 'Waduh, koneksi CS terputus sebentar nih Kak. Mohon coba lagi ya! 🙏', time: this.getTime() });
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = document.getElementById('chat-messages');
                        if (container) container.scrollTop = container.scrollHeight;
                    });
                }
            }));
        });
    </script>

    {{-- ─── Toast Notification ─── --}}
    @if(session('success'))
    <div x-data="{ show: true, progress: 100 }" 
         x-show="show" 
         x-init="
            setTimeout(() => show = false, 3000);
            let interval = setInterval(() => {
                progress -= 1;
                if(progress <= 0) clearInterval(interval);
            }, 30);
         "
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform translate-x-full opacity-0"
         x-transition:enter-end="transform translate-x-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform translate-x-0 opacity-100"
         x-transition:leave-end="transform translate-x-full opacity-0"
         class="fixed bottom-6 right-6 z-[999] bg-white dark:bg-zinc-900 border border-green-200 dark:border-green-900/50 shadow-2xl rounded-xl overflow-hidden"
         style="width: 320px;" x-cloak>
        <div class="p-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Berhasil!</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="h-1 bg-gray-100 dark:bg-zinc-800 w-full">
            <div class="h-full bg-green-500" :style="`width: ${progress}%`"></div>
        </div>
    </div>
    @endif


    <script>
        async function updateCartAJAX(form) {
            const formData = new FormData(form);
            try {
                await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
                });
                
                const res = await fetch(window.location.href);
                const html = await res.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                
                const cartDrawer = document.querySelector('#cart-drawer-inner');
                const newCartDrawer = doc.querySelector('#cart-drawer-inner');
                if(cartDrawer && newCartDrawer) {
                    cartDrawer.innerHTML = newCartDrawer.innerHTML;
                }
                
                const badge = document.querySelector('#cart-badge');
                const newBadge = doc.querySelector('#cart-badge');
                if(badge && newBadge) {
                    badge.innerHTML = newBadge.innerHTML;
                }
            } catch(e) {
                console.error('Cart update failed', e);
            }
        }
    </script>
    @include('components.chat-widget')
</body>
</html>
