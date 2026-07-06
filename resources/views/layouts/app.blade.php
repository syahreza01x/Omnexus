<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', productModalOpen: false, cartOpen: false, mobileMenuOpen: false, selectedProduct: null, navScrolled: false, searchQuery: '' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); window.addEventListener('scroll', () => { let sc = window.scrollY > 20; if(navScrolled !== sc) navScrolled = sc; }, { passive: true })" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- CSS variables matching beranda -->
        <style>
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
                background-color: var(--surface) !important;
                color: var(--text) !important;
                transition: background-color 0.3s, color 0.3s;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow dark:bg-gray-800 dark:border-b dark:border-gray-700">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
