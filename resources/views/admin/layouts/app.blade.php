<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Interco</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 selection:bg-purple-500 selection:text-white">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            class="w-64 bg-gray-900 dark:bg-gray-900 text-gray-100 flex flex-col border-r border-gray-800 transition-all duration-300 relative z-20 shadow-xl">
            <!-- Brand -->
            <div class="px-6 py-6 border-b border-gray-800 flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-purple-500/30">
                    I
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">Interco Admin</h1>
                    @if (auth()->user()->isSuperAdmin())
                        <p class="text-xs text-purple-400 font-medium tracking-wider uppercase mt-0.5">Super Admin</p>
                    @else
                        <p class="text-xs text-indigo-400 font-medium tracking-wider uppercase mt-0.5">
                            {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    @endif
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
                @if (auth()->user()->role === 'admin_web' || auth()->user()->role === 'super_admin')
                    <div class="space-y-1">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest px-3 mb-3">Web
                            Management</p>

                        <a href="{{ route('admin.web.dashboard') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.web.dashboard') ? 'bg-purple-600/10 text-purple-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.web.dashboard') ? 'text-purple-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.web.products') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.web.products*') ? 'bg-purple-600/10 text-purple-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.web.products*') ? 'text-purple-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Kelola Produk
                        </a>

                        <a href="{{ route('admin.web.users') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.web.users*') ? 'bg-purple-600/10 text-purple-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.web.users*') ? 'text-purple-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            List User
                        </a>

                        <a href="{{ route('admin.web.transactions') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.web.transactions*') ? 'bg-purple-600/10 text-purple-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.web.transactions*') ? 'text-purple-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            List Transaksi
                        </a>

                        <a href="{{ route('admin.web.faqs.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.web.faqs*') ? 'bg-purple-600/10 text-purple-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.web.faqs*') ? 'text-purple-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            Kelola FAQ
                        </a>

                        <a href="{{ route('admin.web.custom-orders.index') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.web.custom-orders*') ? 'bg-purple-600/10 text-purple-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.web.custom-orders*') ? 'text-purple-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            Custom Orders
                        </a>

                        <a href="{{ route('admin.web.chat.index') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.web.chat*') ? 'bg-purple-600/10 text-purple-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.web.chat*') ? 'text-purple-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            Chat Customer
                        </a>
                    </div>
                @endif

                @if (auth()->user()->role === 'admin_warehouse' || auth()->user()->role === 'super_admin')
                    <div class="space-y-1">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest px-3 mb-3">Warehouse</p>

                        <a href="{{ route('admin.warehouse.dashboard') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.warehouse.dashboard') ? 'bg-indigo-600/10 text-indigo-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.warehouse.dashboard') ? 'text-indigo-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.warehouse.stocks') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.warehouse.stocks*') ? 'bg-indigo-600/10 text-indigo-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.warehouse.stocks*') ? 'text-indigo-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Kelola Stok
                        </a>

                        <a href="{{ route('admin.warehouse.logs') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.warehouse.logs*') ? 'bg-indigo-600/10 text-indigo-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.warehouse.logs*') ? 'text-indigo-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            History Stok
                        </a>
                    </div>
                @endif

                @if (auth()->user()->role === 'super_admin')
                    <div class="space-y-1">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest px-3 mb-3">Laporan & Analisis</p>

                        <a href="{{ route('admin.super.dashboard') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.super.dashboard') ? 'bg-green-600/10 text-green-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.super.dashboard') ? 'text-green-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                            </svg>
                            Analisis Data
                        </a>

                        <a href="{{ route('admin.super.reports') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.super.reports*') ? 'bg-green-600/10 text-green-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.super.reports*') ? 'text-green-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Laporan Transaksi
                        </a>
                    </div>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Top Bar (Glassmorphism) -->
            <div
                class="sticky top-0 z-10 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <!-- Mobile menu button can go here if needed later -->
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 hidden sm:block">
                            @yield('breadcrumbs', 'Overview')
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode"
                            class="p-2.5 rounded-full text-gray-500 hover:text-indigo-600 bg-gray-100 hover:bg-indigo-50 dark:text-gray-400 dark:hover:text-indigo-400 dark:bg-gray-800 dark:hover:bg-gray-700 transition-all duration-200 focus:outline-none">
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-800 p-1.5 rounded-full transition-colors focus:outline-none">
                                <div
                                    class="w-9 h-9 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden md:block text-left mr-2">
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-200 leading-tight">
                                        {{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                                class="absolute right-0 mt-3 w-56 rounded-xl shadow-lg shadow-gray-200/50 dark:shadow-black/50 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-gray-700 py-2 z-50"
                                x-cloak>

                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 mb-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ auth()->user()->email }}</p>
                                </div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Keluar Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto p-6 md:p-8">
                <div class="max-w-7xl mx-auto">
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
                         class="fixed bottom-6 right-6 z-[999] bg-white dark:bg-gray-900 border border-green-200 dark:border-green-900/50 shadow-2xl rounded-xl overflow-hidden"
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
                        <div class="h-1 bg-gray-100 dark:bg-gray-800 w-full">
                            <div class="h-full bg-green-500" :style="`width: ${progress}%`"></div>
                        </div>
                    </div>
                    @endif

                    @if ($errors->any())
                        <div
                            class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-6 dark:border-red-900/50 dark:bg-red-900/20">
                            <div class="flex items-center gap-3 text-red-700 dark:text-red-400 font-medium mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Ada beberapa kesalahan:
                            </div>
                            <ul class="list-disc pl-10 text-sm text-red-600 dark:text-red-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Inject Content -->
                    <div class="animate-fade-in-up">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
