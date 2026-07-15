<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    productModalOpen: false,
    cartOpen: false,
    selectedProduct: null,
    navScrolled: true,
    modalQty: 1,
    modalSize: 'S',
    formatRp(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    },
    get currentModalPrice() {
        if (!this.selectedProduct) return 0;
        let price = this.selectedProduct.price;
        if (this.selectedProduct.category !== 'Aksesoris') {
            if (this.modalSize === 'M') price += 5000;
            else if (this.modalSize === 'L') price += 10000;
            else if (this.modalSize === 'XL') price += 20000;
            else if (this.modalSize === 'XXL') price += 30000;
        }
        return price;
    },
    cartItems: window.initialCartItems || [],
    get cartSubtotal() {
        return this.cartItems.reduce((total, item) => total + (item.qty * item.price), 0);
    },
    get currentCartCount() {
        return this.cartItems.reduce((sum, item) => sum + parseInt(item.qty), 0);
    },
    updateCartAjax(cartKey, qty) {
        fetch(`/cart/${cartKey}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ _method: 'PATCH', quantity: qty })
        });
    }
}" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.initialCartItems = {!! \Illuminate\Support\Js::from(collect($cart ?? [])->map(function($i, $key) use ($cartProducts) {
            $p = isset($i['product_id']) ? $cartProducts->get((int)$i['product_id']) : null;
            if (!$p) return null;
            $price = (int) $p->price;
            $size = $i['size'] ?? null;
            if ($size && $p->category !== 'Aksesoris') {
                if ($size === 'M') $price += 5000;
                elseif ($size === 'L') $price += 10000;
                elseif ($size === 'XL') $price += 20000;
                elseif ($size === 'XXL') $price += 30000;
            }
            return ['id' => (string)$key, 'qty' => (int)$i['quantity'], 'price' => $price, 'size' => $size];
        })->filter()->values()) !!};
    </script>
    <title>Katalog Produk — Interco</title>
    <meta name="description" content="Lihat semua produk custom Interco. Kaos, Polo, Seragam, Jaket, Aksesoris, Jersey dan lainnya.">

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
            --surface-2: #f4f4f5;
            --text: #18181b;
            --muted: #71717a;
            --border: #e4e4e7;
        }
        .dark {
            --surface: #09090b;
            --surface-2: #18181b;
            --text: #fafafa;
            --muted: #a1a1aa;
            --border: #27272a;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--surface);
            color: var(--text);
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Navbar ─── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .nav-inner {
            max-width: 1280px; margin: 0 auto;
            padding: 0 24px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
        }

        /* ─── Page Header ─── */
        .page-header {
            padding: 120px 0 40px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 700;
            color: var(--text);
            margin: 0 0 8px;
        }
        .page-header p {
            color: var(--muted);
            font-size: 0.95rem;
            margin: 0;
        }

        /* ─── Category Filter ─── */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 24px;
        }
        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 99px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--muted);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
        }
        .filter-pill:hover {
            border-color: rgba(124,58,237,0.4);
            color: var(--accent);
            background: rgba(124,58,237,0.06);
        }
        .filter-pill.active {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
            box-shadow: 0 4px 16px rgba(124,58,237,0.3);
        }
        .dark .filter-pill {
            background: var(--surface-2);
        }
        .dark .filter-pill.active {
            background: var(--accent);
        }

        /* ─── Product Grid ─── */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 40px 0 80px;
        }

        /* ─── Product Cards ─── */
        .product-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .product-card:hover {
            border-color: rgba(124,58,237,0.3);
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        }
        .dark .product-card {
            background: var(--surface-2);
        }
        .dark .product-card:hover {
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        .product-img-wrap {
            position: relative;
            aspect-ratio: 1;
            background: #f9fafb;
            overflow: hidden;
        }
        .dark .product-img-wrap { background: #111; }
        .product-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .product-card:hover .product-img-wrap img { transform: scale(1.08); }
        .product-category-tag {
            position: absolute; top: 12px; left: 12px;
            padding: 4px 10px; border-radius: 8px;
            font-size: 10px; font-weight: 700; letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgba(124,58,237,0.9); color: white;
            z-index: 2;
        }
        .product-overlay {
            position: absolute; inset: 0;
            background: rgba(124,58,237,0.3);
            backdrop-filter: blur(2px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s;
        }
        .product-card:hover .product-overlay { opacity: 1; }
        .product-overlay-btn {
            padding: 10px 20px; border-radius: 12px;
            background: white; color: #7c3aed;
            font-size: 0.82rem; font-weight: 700;
            border: none; cursor: pointer;
            transform: translateY(8px);
            transition: transform 0.3s;
        }
        .product-card:hover .product-overlay-btn { transform: translateY(0); }

        /* ─── Empty State ─── */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 24px;
            border: 2px dashed var(--border);
            border-radius: 20px;
        }

        /* ─── Product Modal ─── */
        .modal-backdrop {
            position: fixed; inset: 0; z-index: 150;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
        }
        .modal-panel {
            position: fixed; inset: 0; z-index: 151;
            display: flex; align-items: center; justify-content: center;
            padding: 16px;
        }
        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            max-width: 520px; width: 100%;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 25px 70px rgba(0,0,0,0.25);
        }

        /* ─── Buttons ─── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 28px;
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: white; border: none; border-radius: 14px;
            font-size: 0.92rem; font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(124,58,237,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(124,58,237,0.45);
        }

        /* ─── Footer ─── */
        .footer {
            background: #09090b;
            border-top: 1px solid #27272a;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 48px;
        }
        .footer-bottom {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 32px; margin-top: 48px;
            border-top: 1px solid #1a1a1a;
        }

        /* ─── Results Count ─── */
        .results-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 0 0;
        }
        .results-count {
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 500;
        }
        .results-count strong {
            color: var(--text);
            font-weight: 700;
        }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
            .catalog-grid { grid-template-columns: repeat(3, 1fr); }
            .footer-grid { grid-template-columns: 1fr; gap: 32px; }
        }
        @media (max-width: 768px) {
            .catalog-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            .filter-bar { gap: 6px; }
            .filter-pill { padding: 6px 14px; font-size: 0.78rem; }
            .footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }
        @media (max-width: 480px) {
            .catalog-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        }

        /* ─── Animations ─── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.5s ease both;
        }
        .animate-in:nth-child(1) { animation-delay: 0.05s; }
        .animate-in:nth-child(2) { animation-delay: 0.1s; }
        .animate-in:nth-child(3) { animation-delay: 0.15s; }
        .animate-in:nth-child(4) { animation-delay: 0.2s; }
        .animate-in:nth-child(5) { animation-delay: 0.25s; }
        .animate-in:nth-child(6) { animation-delay: 0.3s; }
        .animate-in:nth-child(7) { animation-delay: 0.35s; }
        .animate-in:nth-child(8) { animation-delay: 0.4s; }
        .animate-in:nth-child(9) { animation-delay: 0.45s; }
        .animate-in:nth-child(10) { animation-delay: 0.5s; }

        /* Cart item */
        .cart-item {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            transition: all 0.2s;
        }
        .cart-item:hover {
            border-color: rgba(124,58,237,0.3);
        }
    </style>
</head>
<body>
    {{-- ─── Navbar ─── --}}
    <nav class="navbar">
        <div class="nav-inner">
            <a href="{{ route('beranda') }}" class="flex items-center gap-2.5 group" style="text-decoration: none;">
                <img src="{{ asset('images/icon.png') }}" alt="Interco" class="h-8 w-8 object-contain transition-transform duration-300 group-hover:scale-105">
                <span class="text-lg font-bold" style="color: var(--text);">Interco</span>
            </a>

            <div class="hidden md:flex items-center justify-center gap-8 flex-1">
                <a href="{{ route('beranda') }}" class="text-sm font-semibold transition-colors duration-200" style="color: var(--muted); text-decoration: none;" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">Beranda</a>
                <a href="{{ route('products.index') }}" class="text-sm font-semibold transition-colors duration-200" style="color: var(--text); text-decoration: none;">Katalog</a>
            </div>

            <div class="flex items-center gap-2">
                <button @click="darkMode = !darkMode"
                    class="p-2.5 rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-zinc-800"
                    style="color: var(--muted);">
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                @auth
                <button type="button" @click="productModalOpen = false; cartOpen = true"
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-zinc-800"
                    style="color: var(--muted);">
                    @if($cartCount > 0)
                        <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-violet-600 px-1 text-[9px] font-bold text-white ring-2 ring-white dark:ring-zinc-950" x-text="currentCartCount"></span>
                    @endif
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                </button>
                @endauth

                @auth
                <div x-data="{ open: false }" class="relative hidden md:block">
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
                <a href="{{ route('login') }}" class="btn-primary hidden md:inline-flex" style="padding: 10px 20px; font-size: 0.85rem;">
                    Masuk
                </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ─── Page Header + Filters ─── --}}
    <section class="page-header">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <a href="{{ route('beranda') }}" style="font-size: 0.82rem; color: var(--muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--muted)'">Beranda</a>
                <svg style="width: 14px; height: 14px; color: var(--border);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span style="font-size: 0.82rem; color: var(--text); font-weight: 600;">Katalog Produk</span>
            </div>
            <h1>Katalog Produk</h1>
            <p>Temukan semua produk custom berkualitas dari Interco</p>

            <div class="filter-bar">
                <a href="{{ route('products.index') }}"
                    class="filter-pill {{ !$category ? 'active' : '' }}">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat]) }}"
                        class="filter-pill {{ $category === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── Product Grid ─── --}}
    <section style="background: var(--surface);">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
            <div class="results-info">
                <span class="results-count">
                    Menampilkan <strong>{{ $products->count() }}</strong> produk
                    @if($category)
                        dalam kategori <strong>{{ $category }}</strong>
                    @endif
                </span>
            </div>

            <div class="catalog-grid">
                @forelse($products as $i => $product)
                    @php
                        $productData = \Illuminate\Support\Js::from([
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'price' => (int) $product->price,
                            'price_formatted' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                            'image' => asset($product->image_path ?: 'images/items/kaos.png'),
                            'category' => $product->category,
                            'specifications' => $product->specifications,
                            'stock' => $product->stock,
                            'unit' => $product->unit,
                        ]);
                    @endphp
                    <div class="product-card animate-in"
                        @click="cartOpen = false; selectedProduct = {{ $productData }}; modalQty = 1; productModalOpen = true;">
                        <div class="product-img-wrap">
                            @if($product->category)
                                <div class="product-category-tag">{{ $product->category }}</div>
                            @endif
                            <img src="{{ asset($product->image_path ?: 'images/items/kaos.png') }}"
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
                                    @click.stop="cartOpen = false; selectedProduct = {{ $productData }}; modalQty = 1; productModalOpen = true;"
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
                    <div class="empty-state">
                        <svg style="width: 48px; height: 48px; margin: 0 auto 16px; color: var(--border);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 8px;">Tidak Ada Produk</h3>
                        <p style="font-size: 0.9rem; color: var(--muted); margin-bottom: 20px;">Belum ada produk di kategori ini.</p>
                        <a href="{{ route('products.index') }}" class="btn-primary" style="display: inline-flex;">Lihat Semua Produk</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ─── Product Detail Modal ─── --}}
    <div x-cloak x-show="productModalOpen" class="fixed inset-0 z-[150]"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="modal-backdrop" @click="productModalOpen = false"></div>
        <div class="modal-panel">
            <div class="modal-content"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 transform scale-95 translate-y-4">

                <template x-if="selectedProduct">
                    <div>
                        {{-- Image --}}
                        <div style="position: relative; aspect-ratio: 4/3; background: #f9fafb; overflow: hidden;">
                            <img :src="selectedProduct.image" :alt="selectedProduct.name" style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; top: 12px; left: 12px; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; background: rgba(124,58,237,0.9); color: white;" x-text="selectedProduct.category"></div>
                            <button @click="productModalOpen = false" style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #333; transition: all 0.2s;" onmouseover="this.style.background='white'; this.style.transform='scale(1.1)'" onmouseout="this.style.background='rgba(255,255,255,0.9)'; this.style.transform='scale(1)'">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Details --}}
                        <div style="padding: 24px;">
                            <h2 style="font-size: 1.3rem; font-weight: 800; color: var(--text); margin-bottom: 6px; font-family: 'Playfair Display', serif;" x-text="selectedProduct.name"></h2>
                            <p style="font-size: 1.15rem; font-weight: 800; color: #7c3aed; margin-bottom: 16px;" x-text="formatRp(currentModalPrice * modalQty)"></p>
                            <p style="font-size: 0.88rem; color: var(--muted); line-height: 1.7; margin-bottom: 16px;" x-text="selectedProduct.description"></p>

                            <template x-if="selectedProduct.specifications">
                                <div style="margin-bottom: 20px; padding: 14px 16px; background: rgba(124,58,237,0.04); border: 1px solid rgba(124,58,237,0.1); border-radius: 14px;">
                                    <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #7c3aed; margin-bottom: 6px;">Spesifikasi</div>
                                    <p style="font-size: 0.82rem; color: var(--muted); line-height: 1.6;" x-text="selectedProduct.specifications"></p>
                                </div>
                            </template>

                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; font-size: 0.82rem;">
                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; background: rgba(34,197,94,0.1); color: #16a34a; font-weight: 600;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Stok: <span x-text="selectedProduct.stock"></span>
                                </span>
                            </div>

                            @auth
                            <form method="POST" action="{{ route('cart.add') }}" @submit="
                                let item = cartItems.find(i => i.id === selectedProduct.id);
                                if (item) { item.qty += modalQty; } else { cartItems.push({id: selectedProduct.id, qty: modalQty, price: selectedProduct.price}); }
                            ">
                                @csrf
                                <input type="hidden" name="product_id" :value="selectedProduct.id">
                                <div style="display: flex; flex-direction: column; gap: 16px;">
                                    <template x-if="selectedProduct?.category !== 'Aksesoris'">
                                        <div>
                                            <label style="display: block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Ukuran</label>
                                            <div style="display: flex; gap: 8px;">
                                                <template x-for="s in ['S', 'M', 'L', 'XL', 'XXL']" :key="s">
                                                    <button type="button" @click="modalSize = s"
                                                        class="flex-1 py-2.5 flex items-center justify-center rounded-xl text-[0.95rem] font-bold cursor-pointer transition-all duration-200 border"
                                                        :class="modalSize === s ? 'border-violet-600 bg-violet-600 text-white shadow-[0_4px_12px_rgba(124,58,237,0.3)]' : 'border-[var(--border)] bg-[var(--surface-2)] text-[var(--muted)] hover:border-violet-600 hover:text-violet-600'"
                                                        x-text="s">
                                                    </button>
                                                </template>
                                            </div>
                                            <input type="hidden" name="size" x-bind:value="modalSize">
                                        </div>
                                    </template>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                    <div style="display: flex; align-items: center; gap: 0; background: var(--surface-2); border: 1px solid var(--border); border-radius: 12px; overflow: hidden;">
                                        <button type="button" @click="modalQty = Math.max(1, modalQty - 1)" style="width: 40px; height: 44px; border: none; background: transparent; cursor: pointer; font-size: 1.1rem; font-weight: 700; color: var(--muted); display: flex; align-items: center; justify-content: center;">−</button>
                                        <input type="number" name="quantity" x-model.number="modalQty" min="1" style="width: 48px; text-align: center; border: none; background: transparent; font-size: 0.95rem; font-weight: 700; color: var(--text); outline: none; -moz-appearance: textfield;" class="[&::-webkit-inner-spin-button]:appearance-none">
                                        <button type="button" @click="modalQty++" style="width: 40px; height: 44px; border: none; background: transparent; cursor: pointer; font-size: 1.1rem; font-weight: 700; color: var(--muted); display: flex; align-items: center; justify-content: center;">+</button>
                                    </div>
                                    <button type="submit" class="btn-primary" style="flex: 1; justify-content: center; padding: 12px 20px;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                                        Tambah ke Keranjang
                                    </button>
                                    </div>
                                </div>
                            </form>
                            @else
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
                </template>
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
        <div class="absolute inset-0" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);" @click="cartOpen = false"></div>

        <div class="absolute right-0 top-0 h-full overflow-y-auto"
            style="width: min(440px, 100vw); background: var(--surface); box-shadow: -20px 0 60px rgba(0,0,0,0.15);"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="transform translate-x-full"
            x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform translate-x-0"
            x-transition:leave-end="transform translate-x-full">

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 24px; border-bottom: 1px solid var(--border); position: sticky; top: 0; background: var(--surface); z-index: 1;">
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text); font-family: 'Playfair Display', serif;">Keranjang Belanja</h3>
                    <p style="font-size: 0.8rem; color: var(--muted); margin-top: 2px;">{{ $cartCount }} item dipilih</p>
                </div>
                <button type="button" @click="cartOpen = false"
                    style="width: 36px; height: 36px; border-radius: 10px; background: var(--surface-2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted);"
                    onmouseover="this.style.background='var(--border)'"
                    onmouseout="this.style.background='var(--surface-2)'">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div style="padding: 20px; display: flex; flex-direction: column; gap: 12px;">
                @forelse($cart as $cartKey => $cartItem)
                    @php 
                        $cartProduct = $cartProducts->get((int) $cartItem['product_id']); 
                        if ($cartProduct) {
                            $price = (int) $cartProduct->price;
                            $size = $cartItem['size'] ?? null;
                            if ($size && $cartProduct->category !== 'Aksesoris') {
                                if ($size === 'M') $price += 5000;
                                elseif ($size === 'L') $price += 10000;
                                elseif ($size === 'XL') $price += 20000;
                                elseif ($size === 'XXL') $price += 30000;
                            }
                        }
                    @endphp
                    @if($cartProduct)
                        <div class="cart-item">
                            <div style="display: flex; gap: 14px;">
                                <div style="width: 72px; height: 72px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: var(--surface-2);">
                                    <img src="{{ asset($cartProduct->image_path ?: 'images/items/kaos.png') }}"
                                        alt="{{ $cartProduct->name }}"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h4 style="font-size: 0.88rem; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $cartProduct->name }} 
                                        @if($size && $cartProduct->category !== 'Aksesoris')
                                            <span style="font-size: 0.65rem; background: var(--border); padding: 2px 6px; border-radius: 6px; margin-left: 6px; vertical-align: middle;">Ukuran {{ $size }}</span>
                                        @endif
                                    </h4>
                                    <p style="font-size: 0.8rem; font-weight: 600; color: #7c3aed; margin: 4px 0 12px;">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <form method="POST" action="{{ route('cart.update', $cartKey) }}" style="display: flex; align-items: center; gap: 8px;">
                                            @csrf
                                            @method('PATCH')
                                            <div x-data="{ item: cartItems.find(i => i.id === '{{ $cartKey }}') }">
                                                <input type="number" name="quantity" min="1" x-model.number="item.qty" @change="updateCartAjax(item.id, item.qty)"
                                                    style="width: 60px; padding: 6px 10px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.82rem; font-weight: 600; outline: none; text-align: center;">
                                            </div>
                                        </form>
                                        <form method="POST" action="{{ route('cart.remove', $cartKey) }}">
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
                    </div>
                @endforelse
            </div>

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
                    style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; border-radius: 12px; background: #7c3aed; color: white; font-size: 0.92rem; font-weight: 700; text-decoration: none; transition: all 0.3s; margin-bottom: 10px;"
                    onmouseover="this.style.background='#6d28d9'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(124,58,237,0.4)'"
                    onmouseout="this.style.background='#7c3aed'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Checkout
                </a>
                <button type="button" @click="cartOpen = false"
                    style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border); background: transparent; color: var(--muted); font-size: 0.85rem; font-weight: 600; cursor: pointer;"
                    onmouseover="this.style.background='var(--surface-2)'"
                    onmouseout="this.style.background='transparent'">Lanjut Belanja</button>
            </div>
            @endif
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
                </div>
                <div>
                    <h4 style="font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 16px;">Tautan</h4>
                    <ul style="display: flex; flex-direction: column; gap: 10px; list-style: none; padding: 0;">
                        <li><a href="{{ route('beranda') }}" style="font-size: 0.88rem; color: #6b7280; text-decoration: none;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b7280'">Beranda</a></li>
                        <li><a href="{{ route('products.index') }}" style="font-size: 0.88rem; color: #6b7280; text-decoration: none;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b7280'">Katalog Produk</a></li>
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

    @include('components.chat-widget')

</body>
</html>
