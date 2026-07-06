@php
    $cart = session('cart', []);
    $cartCount = collect($cart)->sum('quantity');
@endphp

<!-- CSS Styles specifically matching homepage navbar -->
<style>
    .navbar-custom {
        position: sticky;
        top: 0;
        z-index: 50;
        height: 72px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .dark .navbar-custom {
        background: rgba(15, 15, 19, 0.85);
    }
    .nav-inner-custom {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    /* Button styles matching homepage primary button */
    .btn-primary-custom {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        color: white !important;
        border-radius: 99px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2);
        text-decoration: none;
    }
    .btn-primary-custom:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(124, 58, 237, 0.3);
    }
</style>

<nav class="navbar-custom" id="navbar">
    <div class="nav-inner-custom">
        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2" style="text-decoration: none;">
            <img src="{{ asset('images/logo.png') }}" alt="Interco Logo" class="w-8 h-8 md:w-9 md:h-9 object-contain" onerror="this.src='{{ asset('images/icon.png') }}'">
            <span class="font-bold text-xl md:text-2xl tracking-tight text-gray-900 dark:text-white transition-colors">Interco</span>
        </a>

        {{-- Nav Links --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('beranda') }}" class="text-sm font-semibold transition-all duration-300 {{ request()->routeIs('beranda') ? 'text-violet-600 dark:text-violet-400' : 'text-gray-650 hover:text-violet-600 dark:text-gray-300 dark:hover:text-violet-400' }}" style="text-decoration: none;">Beranda</a>
            <a href="{{ route('beranda') }}#produk" class="text-sm font-semibold transition-all duration-300 text-gray-650 hover:text-violet-600 dark:text-gray-300 dark:hover:text-violet-400" style="text-decoration: none;">Produk</a>
            <a href="{{ route('beranda') }}#kategori" class="text-sm font-semibold transition-all duration-300 text-gray-650 hover:text-violet-600 dark:text-gray-300 dark:hover:text-violet-400" style="text-decoration: none;">Kategori</a>
            <a href="{{ route('beranda') }}#testimoni" class="text-sm font-semibold transition-all duration-300 text-gray-650 hover:text-violet-600 dark:text-gray-300 dark:hover:text-violet-400" style="text-decoration: none;">Testimoni</a>
            <a href="{{ route('custom-orders.index') }}" class="text-sm font-semibold transition-all duration-300 {{ request()->routeIs('custom-orders.*') ? 'text-violet-600 dark:text-violet-400' : 'text-gray-650 hover:text-violet-600 dark:text-gray-300 dark:hover:text-violet-400' }}" style="text-decoration: none;">Custom Order</a>
        </div>

        {{-- Right Actions --}}
        <div class="flex items-center gap-1 md:gap-2">
            {{-- Dark toggle --}}
            <button @click="darkMode = !darkMode"
                class="p-2 md:p-2.5 rounded-xl transition-all duration-200 text-gray-500 hover:text-violet-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-violet-400 dark:hover:bg-zinc-800">
                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            {{-- Cart Button --}}
            @auth
            <a href="{{ route('beranda') }}"
                class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50 text-gray-500 transition-colors hover:bg-gray-100 dark:bg-zinc-900 dark:text-gray-400 dark:hover:bg-zinc-800 dark:hover:text-gray-300">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                @if($cartCount > 0)
                    <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-violet-600 px-1 text-[9px] font-bold text-white ring-2 ring-white dark:ring-zinc-950">{{ $cartCount }}</span>
                @endif
            </a>
            @endauth

            {{-- Profile Dropdown --}}
            @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center justify-center p-2 md:p-2.5 rounded-xl transition-all duration-200 text-gray-500 hover:text-violet-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-violet-400 dark:hover:bg-zinc-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition x-cloak
                        class="absolute right-0 mt-2 w-52 rounded-2xl shadow-xl border py-2 z-50"
                        style="background: var(--surface); border-color: var(--border);">
                        <div class="px-4 py-3 border-b" style="border-color: var(--border);">
                            <p class="text-sm font-semibold truncate" style="color: var(--text)">{{ auth()->user()->name }}</p>
                            <p class="text-xs truncate" style="color: var(--muted)">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors text-gray-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-zinc-800" style="text-decoration: none;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>
                        <a href="{{ route('custom-orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors text-gray-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-zinc-800" style="text-decoration: none;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Custom Order Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left transition-colors text-gray-600 dark:text-gray-300 hover:bg-red-50 hover:text-red-650 dark:hover:bg-red-950/20 dark:hover:text-red-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-primary-custom px-4 py-2 text-xs">
                    Masuk
                </a>
            @endauth

            {{-- Hamburger Menu (Mobile) --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-zinc-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenuOpen" x-transition.opacity x-cloak class="md:hidden border-t" style="border-color: var(--border); background: var(--surface); position: absolute; width: 100%; top: 100%; left: 0; z-index: 40;">
        <div class="flex flex-col py-4 px-6 space-y-4">
            <a href="{{ route('beranda') }}" class="text-sm font-semibold text-gray-650 dark:text-gray-300" style="text-decoration: none;">Beranda</a>
            <a href="{{ route('beranda') }}#produk" class="text-sm font-semibold text-gray-650 dark:text-gray-300" style="text-decoration: none;">Produk</a>
            <a href="{{ route('beranda') }}#kategori" class="text-sm font-semibold text-gray-650 dark:text-gray-300" style="text-decoration: none;">Kategori</a>
            <a href="{{ route('beranda') }}#testimoni" class="text-sm font-semibold text-gray-650 dark:text-gray-300" style="text-decoration: none;">Testimoni</a>
            <a href="{{ route('custom-orders.index') }}" class="text-sm font-semibold text-gray-650 dark:text-gray-300" style="text-decoration: none;">Custom Order</a>
        </div>
    </div>
</nav>
