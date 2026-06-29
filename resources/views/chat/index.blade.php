<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat Support — Interco</title>

    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        :root {
            --accent: #7c3aed;
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
            font-family: 'Inter', sans-serif;
            background: var(--surface);
            color: var(--text);
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .page-topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chat-container {
            flex: 1;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            border-left: 1px solid var(--border);
            border-right: 1px solid var(--border);
            background: var(--surface-2);
        }
        .chat-messages {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .msg {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .msg-admin {
            align-self: flex-start;
            background: var(--surface);
            border: 1px solid var(--border);
            border-bottom-left-radius: 4px;
        }
        .msg-user {
            align-self: flex-end;
            background: #7c3aed;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        .msg-time {
            font-size: 0.75rem;
            margin-top: 4px;
            opacity: 0.7;
            text-align: right;
        }
        .chat-input {
            padding: 20px;
            background: var(--surface);
            border-top: 1px solid var(--border);
            display: flex;
            gap: 12px;
        }
        .chat-input input {
            flex: 1;
            padding: 12px 16px;
            border-radius: 99px;
            border: 1px solid var(--border);
            background: var(--surface-2);
            color: var(--text);
            outline: none;
        }
        .chat-input button {
            background: #7c3aed;
            color: white;
            border: none;
            padding: 0 24px;
            border-radius: 99px;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="page-topbar">
        <a href="{{ route('beranda') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
            <span style="font-size: 1.1rem; font-weight: 800; color: var(--text); font-family: 'Playfair Display', serif;">← Kembali</span>
        </a>
        <div style="font-weight: 700;">Chat Support Interco</div>
        <div style="width: 70px;"></div>
    </header>

    <div class="chat-container">
        <div class="chat-messages" id="chatMessages">
            @if($messages->isEmpty())
                <div style="text-align: center; color: var(--muted); margin-top: 40px;">
                    Halo! Ada yang bisa kami bantu?
                </div>
            @endif

            @foreach($messages as $msg)
                <div class="msg {{ $msg->is_from_admin ? 'msg-admin' : 'msg-user' }}">
                    <div>{{ $msg->message }}</div>
                    <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                </div>
            @endforeach
        </div>
        <form action="{{ route('chat.store') }}" method="POST" class="chat-input">
            @csrf
            <input type="text" name="message" placeholder="Ketik pesan..." required autofocus autocomplete="off">
            <button type="submit">Kirim</button>
        </form>
    </div>

    <script>
        // Scroll to bottom
        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    </script>
</body>
</html>
