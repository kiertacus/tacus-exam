<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Initiate a voice or video call
     */
    public function initiate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'recipient_id' => ['required', 'exists:users,id'],
            'call_type' => ['required', 'in:voice,video'],
        ]);

        // Create a call message
        $message = Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $validated['recipient_id'],
            'content' => 'Call initiated',
            'call_type' => $validated['call_type'],
            'call_status' => 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Call initiated',
            'call_id' => $message->id,
            'call_type' => $validated['call_type'],
        ]);
    }

    /**
     * End a call and log duration
     */
    public function end(Request $request, Message $message): JsonResponse
    {
        $this->authorize('update', $message);

        $validated = $request->validate([
            'duration' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:completed,missed,declined'],
        ]);

        $message->update([
            'call_duration' => $validated['duration'],
            'call_status' => $validated['status'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Call ended',
        ]);
    }

    /**
     * Decline a call
     */
    public function decline(Message $message): RedirectResponse
    {
        $this->authorize('update', $message);

        $message->update([
            'call_status' => 'declined',
        ]);

        return back()->with('status', 'Call declined');
    }
}
