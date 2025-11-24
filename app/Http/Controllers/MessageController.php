<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show all conversations
    public function conversations()
    {
        $user = Auth::user();
        $conversations = User::where('id', '!=', $user->id)
            ->get()
            ->map(function ($otherUser) use ($user) {
                $lastMessage = Message::where(function ($query) use ($user, $otherUser) {
                    $query->where('sender_id', $user->id)
                        ->where('recipient_id', $otherUser->id);
                })->orWhere(function ($query) use ($user, $otherUser) {
                    $query->where('sender_id', $otherUser->id)
                        ->where('recipient_id', $user->id);
                })->latest()->first();
                
                return [
                    'user' => $otherUser,
                    'last_message' => $lastMessage,
                    'unread_count' => Message::where('sender_id', $otherUser->id)
                        ->where('recipient_id', $user->id)
                        ->where('read', false)
                        ->count()
                ];
            })
            ->filter(function ($conv) {
                return $conv['last_message'] !== null;
            })
            ->sortBy(function ($conv) {
                return $conv['last_message']->created_at;
            }, SORT_REGULAR, true)
            ->values();

        return view('messages.conversations', [
            'conversations' => $conversations,
        ]);
    }

    // Show chat with specific user
    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('recipient_id', $currentUser->id)
            ->update(['read' => true]);

        $messages = Message::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                ->where('recipient_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                ->where('recipient_id', $currentUser->id);
        })->orderBy('created_at')->get();

        return view('messages.show', [
            'user' => $user,
            'messages' => $messages,
        ]);
    }

    // Store a new message
    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $user->id,
            'content' => $request->content,
        ]);

        return redirect()->route('messages.show', $user)->with('success', 'Message sent!');
    }
}
