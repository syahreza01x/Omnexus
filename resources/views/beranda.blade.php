<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interco - Beranda</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">

    {{-- Navbar --}}
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2 text-xl font-bold text-purple-600 dark:text-purple-400">
                    <img src="{{ asset('images/icon.png') }}" alt="Interco" class="h-8 w-8 object-contain">
                    Interco
                </a>

                {{-- Search Bar --}}
                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <div class="relative w-full">
                        <input type="text" placeholder="Cari produk..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:text-gray-200 dark:placeholder-gray-400">
                        <svg class="absolute right-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Right side: dark mode toggle + auth --}}
                <div class="flex items-center gap-3">
                    {{-- Dark/Light toggle --}}
                    <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        {{-- Sun icon (show in dark mode) --}}
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        {{-- Moon icon (show in light mode) --}}
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    @auth
                        {{-- User icon dropdown --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center justify-center w-9 h-9 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                                <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 font-medium transition">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Banner --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 dark:from-black dark:via-gray-900 dark:to-black text-white">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.15&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase bg-white/10 border border-white/20 rounded-full mb-6">Custom Order</span>
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                        Wujudkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Pakaian Impian</span> Anda
                    </h1>
                    <p class="text-lg text-gray-300 mb-8 leading-relaxed">
                        Desain pakaian custom sesuai keinginan Anda. Dari bahan, warna, hingga detail jahitan — semua bisa disesuaikan.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#produk" class="inline-flex items-center gap-2 bg-white text-gray-900 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                            Lihat Katalog
                        </a>
                        <a href="#" class="inline-flex items-center gap-2 border border-white/30 text-white font-semibold px-6 py-3 rounded-lg hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                            </svg>
                            Custom Order
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex justify-center">
                    <div class="relative">
                        <div class="w-72 h-72 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                            <svg class="w-40 h-40 text-white/80" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-16 h-16 bg-purple-500/30 rounded-full blur-xl"></div>
                        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-pink-500/30 rounded-full blur-xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Kategori</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih jenis pakaian yang ingin Anda custom</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-5">
            {{-- Aksesoris --}}
            <a href="#" class="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-gray-900 dark:hover:border-gray-400 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-900 dark:group-hover:bg-white transition-all duration-300 mb-3">
                    <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 group-hover:text-white dark:group-hover:text-gray-900 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Aksesoris</span>
            </a>

            {{-- Pakaian --}}
            <a href="#" class="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-gray-900 dark:hover:border-gray-400 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-900 dark:group-hover:bg-white transition-all duration-300 mb-3">
                    <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 group-hover:text-white dark:group-hover:text-gray-900 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 3l-5 4 2 2 2-1v12a1 1 0 001 1h8a1 1 0 001-1V8l2 1 2-2-5-4-1.5 2h-5L8 3z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Pakaian</span>
            </a>

            {{-- Outer --}}
            <a href="#" class="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-gray-900 dark:hover:border-gray-400 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-900 dark:group-hover:bg-white transition-all duration-300 mb-3">
                    <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 group-hover:text-white dark:group-hover:text-gray-900 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3L4 7l1.5 1.5L7 7.5V20a1 1 0 001 1h3v-6h2v6h3a1 1 0 001-1V7.5l1.5 1L20 7l-5-4h-1l-1 2h-2L9 3zM7 10v3M17 10v3"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Outer</span>
            </a>

            {{-- Kemeja --}}
            <a href="#" class="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-gray-900 dark:hover:border-gray-400 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-900 dark:group-hover:bg-white transition-all duration-300 mb-3">
                    <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 group-hover:text-white dark:group-hover:text-gray-900 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 3l-4 4 3 2V20a1 1 0 001 1h10a1 1 0 001-1V9l3-2-4-4-2 3h-6L7 3zM10 3v4l2 1 2-1V3"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Kemeja</span>
            </a>

            {{-- Rompi --}}
            <a href="#" class="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-gray-900 dark:hover:border-gray-400 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-900 dark:group-hover:bg-white transition-all duration-300 mb-3">
                    <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 group-hover:text-white dark:group-hover:text-gray-900 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3l-2 3v14a1 1 0 001 1h8a1 1 0 001-1V6l-2-3h-6zM9 3L7 5M15 3l2 2M9 8h6M9 3c0 2 1.5 3 3 3s3-1 3-3"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Rompi</span>
            </a>
        </div>
    </section>

    {{-- Produk Custom --}}
    <section id="produk" class="bg-gray-50 dark:bg-gray-800/50 py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Produk Custom Populer</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Inspirasi desain dari pesanan pelanggan kami</p>
                </div>
                <a href="#" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            @php
                $products = [
                    ['name' => 'Kemeja Flannel Custom', 'desc' => 'Bahan premium, desain bebas pilih', 'price' => 185000, 'badge' => 'Best Seller'],
                    ['name' => 'Outer Hoodie Oversize', 'desc' => 'Fleece tebal, sablon & bordir', 'price' => 220000, 'badge' => 'New'],
                    ['name' => 'Rompi Kerja Formal', 'desc' => 'Cutting presisi, bahan kantor', 'price' => 165000, 'badge' => null],
                    ['name' => 'Kaos Polos Custom', 'desc' => 'Cotton combed 30s, warna bebas', 'price' => 95000, 'badge' => 'Populer'],
                    ['name' => 'Jaket Varsity Custom', 'desc' => 'Kombinasi fleece & parasut', 'price' => 275000, 'badge' => null],
                    ['name' => 'Kemeja Batik Modern', 'desc' => 'Motif custom, slim fit', 'price' => 210000, 'badge' => 'New'],
                    ['name' => 'Aksesoris Topi Bucket', 'desc' => 'Bordir custom logo & teks', 'price' => 75000, 'badge' => null],
                    ['name' => 'Outer Parka Custom', 'desc' => 'Waterproof, desain bebas', 'price' => 310000, 'badge' => 'Premium'],
                ];
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-lg transition-all duration-300 overflow-hidden group">
                        <div class="relative aspect-square bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            @if($product['badge'])
                                <span class="absolute top-3 left-3 text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-900 dark:bg-white text-white dark:text-gray-900">{{ $product['badge'] }}</span>
                            @endif
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1 group-hover:text-gray-900 dark:group-hover:text-white transition">{{ $product['name'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ $product['desc'] }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-base font-bold text-gray-900 dark:text-white">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-900 dark:hover:bg-white text-gray-600 dark:text-gray-400 hover:text-white dark:hover:text-gray-900 transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Kenapa Custom di Sini --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3 text-center">Kenapa Custom di Interco?</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-10 max-w-xl mx-auto">Kami menghadirkan pengalaman custom order yang mudah, cepat, dan berkualitas tinggi</p>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-100">Desain Bebas</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Upload desain sendiri atau konsultasi dengan tim desainer kami untuk hasil terbaik.</p>
            </div>
            <div class="text-center p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.385 3.17a.75.75 0 01-1.088-.79l1.028-5.99-4.353-4.242a.75.75 0 01.416-1.279l6.015-.874L11.065.93a.75.75 0 011.37 0l2.692 5.455 6.015.874a.75.75 0 01.416 1.28l-4.353 4.24 1.028 5.99a.75.75 0 01-1.088.791L12 15.17l-5.385 3.17z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-100">Bahan Premium</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Hanya menggunakan bahan berkualitas tinggi yang nyaman dan tahan lama.</p>
            </div>
            <div class="text-center p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-100">Pengerjaan Cepat</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Proses produksi 3-7 hari kerja dengan pengiriman ke seluruh Indonesia.</p>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 dark:bg-gray-950 text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-white font-bold text-lg mb-3 flex items-center gap-2">
                        <img src="{{ asset('images/icon.png') }}" alt="Interco" class="h-6 w-6 object-contain">
                        Interco
                    </h4>
                    <p class="text-sm">Belanja online terpercaya dengan produk berkualitas dan harga terjangkau.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Tautan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Hubungi Kami</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Email: info@interco.com</li>
                        <li>Telepon: (021) 1234-5678</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm">
                &copy; {{ date('Y') }} Interco. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
