<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Interco</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-gray-100 flex flex-col">
            <div class="px-6 py-6 border-b border-gray-800">
                <h1 class="text-xl font-bold">Interco Admin</h1>
                @if (auth()->user()->isSuperAdmin())
                    <p class="text-xs text-gray-400 mt-1">Super Admin</p>
                @else
                    <p class="text-xs text-gray-400 mt-1">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</p>
                @endif
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
                @if (auth()->user()->role === 'admin_web' || auth()->user()->role === 'super_admin')
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase px-3 py-2">Admin Web</p>
                        <a href="{{ route('admin.web.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm !text-gray-100 hover:bg-gray-800 transition">Dashboard</a>
                        <a href="{{ route('admin.web.products') }}" class="block px-3 py-2 rounded-lg text-sm !text-gray-100 hover:bg-gray-800 transition">Kelola Produk</a>
                        <a href="{{ route('admin.web.users') }}" class="block px-3 py-2 rounded-lg text-sm !text-gray-100 hover:bg-gray-800 transition">List User</a>
                        <a href="{{ route('admin.web.transactions') }}" class="block px-3 py-2 rounded-lg text-sm !text-gray-100 hover:bg-gray-800 transition">List Transaksi</a>
                    </div>
                @endif

                @if (auth()->user()->role === 'admin_warehouse' || auth()->user()->role === 'super_admin')
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase px-3 py-2 mt-4">Admin Gudang</p>
                        <a href="{{ route('admin.warehouse.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm !text-gray-100 hover:bg-gray-800 transition">Dashboard</a>
                        <a href="{{ route('admin.warehouse.stocks') }}" class="block px-3 py-2 rounded-lg text-sm !text-gray-100 hover:bg-gray-800 transition">Kelola Stok</a>
                        <a href="{{ route('admin.warehouse.logs') }}" class="block px-3 py-2 rounded-lg text-sm !text-gray-100 hover:bg-gray-800 transition">History Stok</a>
                    </div>
                @endif
            </nav>

            <div class="border-t border-gray-800 px-4 py-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-gray-800 transition">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                <div class="px-4 py-4 flex justify-between items-center">
                    <div></div>
                    <div class="flex items-center gap-4">
                        <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white text-sm font-bold hover:bg-purple-700 transition">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-gray-800 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto p-8">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-6 py-4 text-sm text-green-700 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-6 py-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
