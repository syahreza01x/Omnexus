@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Daftar User</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola user yang terdaftar di sistem</p>
</div>

<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.web.users') }}" class="flex gap-4">
            <input type="text" name="search" placeholder="Cari user..." value="{{ request('search') }}" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
            <button type="submit" class="rounded-lg bg-gray-600 px-6 py-2 text-white font-medium hover:bg-gray-700">Cari</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Terdaftar</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Status Email</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $user->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if ($user->email_verified_at)
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Terverifikasi</span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
        {{ $users->links() }}
    </div>
</div>
@endsection
