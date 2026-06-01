<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Interco</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-white via-gray-50 to-purple-100 text-gray-900 antialiased transition-colors duration-300 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800 dark:text-gray-100">
    <div class="mx-auto flex min-h-screen w-full max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid w-full gap-6 rounded-3xl border border-gray-200 bg-white/90 p-5 shadow-xl backdrop-blur md:grid-cols-2 md:p-10 dark:border-gray-700 dark:bg-gray-900/80">
            <div class="rounded-2xl border-2 border-gray-300 bg-white p-8 text-gray-900 dark:border-gray-700 dark:bg-gray-950 dark:text-white">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white">
                    <img src="{{ asset('images/icon.png') }}" alt="Interco" class="h-8 w-8 object-contain">
                    Interco
                </a>
                <h1 class="mt-10 text-3xl font-bold leading-tight">Buat akun baru</h1>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Daftar untuk mulai custom order lebih cepat dan pantau status pesanan Anda.</p>
                <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700 dark:border-white/20 dark:bg-white/5 dark:text-gray-200">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-purple-700 hover:text-purple-800 dark:text-purple-300 dark:hover:text-purple-200">Masuk sekarang</a>
                </div>
            </div>

            <div class="p-2 md:p-4">
                <a href="{{ route('auth.google.redirect') }}" class="mb-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.2-1.4 3.6-5.5 3.6-3.3 0-6-2.7-6-6s2.7-6 6-6c1.9 0 3.1.8 3.8 1.5l2.6-2.5C16.7 3.2 14.6 2.3 12 2.3 6.9 2.3 2.8 6.4 2.8 11.5S6.9 20.7 12 20.7c6.9 0 9.1-4.8 9.1-7.2 0-.5 0-.8-.1-1.2H12z"/>
                    </svg>
                    Daftar dengan Google
                </a>

                <div class="relative mb-5">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        <span class="bg-white px-2 dark:bg-gray-900">atau</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="username" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-purple-400 dark:focus:ring-purple-900/60">
                        @error('username')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-purple-400 dark:focus:ring-purple-900/60">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-purple-400 dark:focus:ring-purple-900/60">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-purple-400 dark:focus:ring-purple-900/60">
                        @error('password_confirmation')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-black dark:bg-purple-600 dark:hover:bg-purple-500">
                        Daftar
                    </button>
                </form>

                <p class="mt-5 text-center text-sm text-gray-600 dark:text-gray-300">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-purple-700 hover:text-purple-800">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
