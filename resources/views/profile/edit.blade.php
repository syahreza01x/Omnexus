<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil - Interco</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html:not(.dark) nav .brand {
            color: #111827 !important;
            opacity: 1 !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('beranda') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/icon.png') }}" alt="Interco" class="h-8 w-8 object-contain">
                    <span class="brand text-xl font-bold text-gray-900 dark:text-white">Interco</span>
                </a>

                <div class="flex items-center gap-3">
                    <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600" title="Keranjang">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                        </svg>
                    </a>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center justify-center w-9 h-9 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-44 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: '{{ in_array($activeTab, ['profile', 'security', 'address', 'account']) ? $activeTab : 'profile' }}', editAddressId: null }">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Akun Saya</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola informasi profil, keamanan akun, dan alamat pengiriman.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
                @switch(session('status'))
                    @case('basic-updated')
                        Profil berhasil diperbarui.
                        @break
                    @case('email-updated')
                        Email berhasil diperbarui.
                        @break
                    @case('address-created')
                        Alamat baru berhasil ditambahkan.
                        @break
                    @case('address-updated')
                        Alamat berhasil diperbarui.
                        @break
                    @case('address-default-set')
                        Alamat utama berhasil diubah.
                        @break
                    @case('address-deleted')
                        Alamat berhasil dihapus.
                        @break
                    @case('password-updated')
                        Password berhasil diperbarui.
                        @break
                    @default
                        Perubahan berhasil disimpan.
                @endswitch
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-4">
            <aside class="lg:col-span-1">
                <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                    <button @click="tab = 'profile'" class="w-full rounded-xl px-4 py-3 text-left text-sm font-medium transition"
                        :class="tab === 'profile' ? 'bg-gray-900 text-white dark:bg-purple-600' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'">
                        Profil
                    </button>
                    <button @click="tab = 'security'" class="mt-2 w-full rounded-xl px-4 py-3 text-left text-sm font-medium transition"
                        :class="tab === 'security' ? 'bg-gray-900 text-white dark:bg-purple-600' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'">
                        Keamanan
                    </button>
                    <button @click="tab = 'address'" class="mt-2 w-full rounded-xl px-4 py-3 text-left text-sm font-medium transition"
                        :class="tab === 'address' ? 'bg-gray-900 text-white dark:bg-purple-600' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'">
                        Alamat
                    </button>
                    <button @click="tab = 'account'" class="mt-2 w-full rounded-xl px-4 py-3 text-left text-sm font-medium transition"
                        :class="tab === 'account' ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'">
                        Akun
                    </button>
                </div>
            </aside>

            <section class="lg:col-span-3 space-y-6">
                <div x-show="tab === 'profile'" x-cloak class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Profil</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ubah foto profil dan nama pengguna Anda.</p>

                    <form method="POST" action="{{ route('profile.basic.update') }}" enctype="multipart/form-data" class="mt-5 space-y-5">
                        @csrf
                        @method('PATCH')

                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 overflow-hidden rounded-full border border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700">
                                @if ($user->profile_photo_path)
                                    <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="Foto profil" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-sm font-semibold text-gray-500 dark:text-gray-300">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Foto Profil</label>
                                <input id="photo" name="photo" type="file" accept="image/*" class="mt-1 block text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-black dark:text-gray-300 dark:file:bg-purple-600 dark:hover:file:bg-purple-500">
                                @error('photo')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Pengguna</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-black dark:bg-purple-600 dark:hover:bg-purple-500">
                            Simpan Profil
                        </button>
                    </form>
                </div>

                <div x-show="tab === 'security'" x-cloak class="space-y-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Email</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui email untuk login dan notifikasi.</p>

                        <form method="POST" action="{{ route('profile.email.update') }}" class="mt-5 space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-black dark:bg-purple-600 dark:hover:bg-purple-500">
                                Simpan Email
                            </button>
                        </form>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Password</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gunakan password yang kuat untuk melindungi akun.</p>

                        <form method="POST" action="{{ route('password.update') }}" class="mt-5 space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="current_password" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Password Saat Ini</label>
                                <input id="current_password" name="current_password" type="password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @if ($errors->updatePassword->get('current_password'))
                                    <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="password" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Password Baru</label>
                                <input id="password" name="password" type="password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @if ($errors->updatePassword->get('password'))
                                    <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Konfirmasi Password Baru</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            </div>
                            <button type="submit" class="rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-black dark:bg-purple-600 dark:hover:bg-purple-500">
                                Ganti Password
                            </button>
                        </form>
                    </div>
                </div>

                <div x-show="tab === 'address'" x-cloak class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Alamat</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Atur alamat utama untuk kebutuhan checkout.</p>

                    <!-- Existing Addresses List -->
                    @if ($addresses->count() > 0)
                        <div class="mt-6 space-y-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Tersimpan</h3>
                            @foreach ($addresses as $address)
                                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $address->label ?? 'Alamat' }}
                                                </h4>
                                                @if ($address->is_default)
                                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-300">Utama</span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $address->address_line }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                            @if ($address->phone)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $address->phone }}</p>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex items-center gap-2">
                                            @if (!$address->is_default)
                                                <form method="POST" action="{{ route('address.set-default', $address) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Jadikan Utama</button>
                                                </form>
                                            @endif
                                            <button @click="editAddressId = {{ $address->id }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                            <form method="POST" action="{{ route('address.delete', $address) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Form Add/Edit Address -->
                    <form method="POST" action="{{ route('address.store') }}" class="mt-8 grid gap-4 md:grid-cols-2 rounded-xl border border-gray-200 p-6 dark:border-gray-700">
                        @csrf
                        <h3 class="col-span-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tambah Alamat Baru</h3>

                        <div class="md:col-span-2">
                            <label for="label" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Label (Rumah, Kantor, dll)</label>
                            <input id="label" name="label" type="text" placeholder="Rumah" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('label')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address_line" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Alamat Lengkap</label>
                            <input id="address_line" name="address_line" type="text" placeholder="Jl. Contoh No. 123" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('address_line')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Kota</label>
                            <input id="city" name="city" type="text" placeholder="Padang" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('city')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Provinsi</label>
                            <input id="province" name="province" type="text" placeholder="Sumatra Barat" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('province')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Kode Pos</label>
                            <input id="postal_code" name="postal_code" type="text" placeholder="25000" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('postal_code')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">No. Telepon</label>
                            <input id="phone" name="phone" type="text" placeholder="081234567890" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 flex items-center gap-3">
                            <label for="is_default" class="flex items-center gap-2 cursor-pointer">
                                <input id="is_default" name="is_default" type="checkbox" value="1" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:border-gray-700 dark:bg-gray-900">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Jadikan alamat utama</span>
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            <button type="submit" class="rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-black dark:bg-purple-600 dark:hover:bg-purple-500">
                                Simpan Alamat
                            </button>
                        </div>
                    </form>

                    <!-- Edit Address Modal -->
                    @if ($addresses->count() > 0)
                        @foreach ($addresses as $address)
                            <div x-show="editAddressId === {{ $address->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-lg dark:bg-gray-800">
                                    <div class="mb-4 flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Edit Alamat</h3>
                                        <button @click="editAddressId = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <form method="POST" action="{{ route('address.update', $address) }}" class="space-y-4">
                                        @csrf
                                        @method('PATCH')

                                        <div>
                                            <label for="edit_label_{{ $address->id }}" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Label</label>
                                            <input id="edit_label_{{ $address->id }}" name="label" type="text" value="{{ $address->label }}" placeholder="Rumah" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                        </div>

                                        <div>
                                            <label for="edit_address_{{ $address->id }}" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Alamat Lengkap</label>
                                            <input id="edit_address_{{ $address->id }}" name="address_line" type="text" value="{{ $address->address_line }}" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label for="edit_city_{{ $address->id }}" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Kota</label>
                                                <input id="edit_city_{{ $address->id }}" name="city" type="text" value="{{ $address->city }}" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                            </div>
                                            <div>
                                                <label for="edit_province_{{ $address->id }}" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Provinsi</label>
                                                <input id="edit_province_{{ $address->id }}" name="province" type="text" value="{{ $address->province }}" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label for="edit_postal_{{ $address->id }}" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Kode Pos</label>
                                                <input id="edit_postal_{{ $address->id }}" name="postal_code" type="text" value="{{ $address->postal_code }}" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                            </div>
                                            <div>
                                                <label for="edit_phone_{{ $address->id }}" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Telepon</label>
                                                <input id="edit_phone_{{ $address->id }}" name="phone" type="text" value="{{ $address->phone }}" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                            </div>
                                        </div>

                                        <div class="flex justify-end gap-2 pt-4">
                                            <button type="button" @click="editAddressId = null" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                                Batal
                                            </button>
                                            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black dark:bg-purple-600 dark:hover:bg-purple-500">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div x-show="tab === 'account'" x-cloak class="rounded-2xl border border-red-200 bg-white p-6 dark:border-red-900 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-red-600 dark:text-red-400">Hapus Akun</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Aksi ini permanen. Semua data akun akan dihapus.</p>

                    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-5 space-y-4 max-w-md">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label for="delete_password" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Konfirmasi Password</label>
                            <input id="delete_password" name="password" type="password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            @if ($errors->userDeletion->get('password'))
                                <p class="mt-1 text-xs text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                            @endif
                        </div>
                        <button type="submit" class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                            Hapus Akun
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
