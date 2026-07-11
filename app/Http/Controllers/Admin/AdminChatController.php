<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminChatController extends Controller
{
    public function index(Request $request): View
    {
        // Get users who have messages, ordered by the latest message
        $users = User::whereHas('messages')
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_from_admin', false)->where('is_read', false);
            }])
            ->with(['messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function ($user) {
                return $user->messages->first()->created_at ?? now();
            });

        return view('admin.web.chat.index', compact('users'));
    }

    public function show(Request $request, User $user): View
    {
        $messages = Message::where('user_id', $user->id)
            ->oldest()
            ->get();

        // Mark unread messages from user as read
        Message::where('user_id', $user->id)
            ->where('is_from_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.web.chat.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        Message::create([
            'user_id' => $user->id,
            'message' => $validated['message'],
            'is_from_admin' => true,
        ]);

        return redirect()->route('admin.web.chat.show', $user);
    }
}
