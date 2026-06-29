<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Message;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $messages = Message::where('user_id', $request->user()->id)
            ->oldest()
            ->get();

        // Mark unread messages from admin as read
        Message::where('user_id', $request->user()->id)
            ->where('is_from_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.index', compact('messages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        Message::create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
            'is_from_admin' => false,
        ]);

        return redirect()->route('chat.index');
    }
}
