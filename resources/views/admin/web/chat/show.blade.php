@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Chat dengan {{ $user->name }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $user->email }}</p>
    </div>
    <a href="{{ route('admin.web.chat.index') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">← Kembali ke Daftar</a>
</div>

<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 flex flex-col" style="height: 600px;">
    <!-- Messages Area -->
    <div class="flex-1 p-6 overflow-y-auto flex flex-col gap-4" id="chatMessages">
        @forelse($messages as $msg)
            <div class="max-w-[75%] px-4 py-3 rounded-2xl text-sm {{ $msg->is_from_admin ? 'self-end bg-purple-600 text-white rounded-br-sm' : 'self-start bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-bl-sm' }}">
                <div>{{ $msg->message }}</div>
                <div class="text-[0.7rem] mt-1 {{ $msg->is_from_admin ? 'text-purple-200 text-right' : 'text-gray-500 dark:text-gray-400' }}">
                    {{ $msg->created_at->format('d M H:i') }}
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 dark:text-gray-400 mt-10">Belum ada pesan.</div>
        @endforelse
    </div>

    <!-- Input Area -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-b-xl">
        <form action="{{ route('admin.web.chat.store', $user) }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="message" placeholder="Ketik balasan..." required autofocus autocomplete="off" class="flex-1 rounded-full border border-gray-300 px-5 py-3 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            <button type="submit" class="rounded-full bg-purple-600 px-8 py-3 text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">Kirim</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });
</script>
@endsection
