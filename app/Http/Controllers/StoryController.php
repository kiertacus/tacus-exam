<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use App\Notifications\StoryPostedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Notification;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new story
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'media' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm', 'max:51200'],
            'caption' => ['nullable', 'string', 'max:500'],
        ]);

        $file = $request->file('media');
        $type = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';
        $path = $file->store('stories', 'public');

        $story = $request->user()->stories()->create([
            'media_path' => $path,
            'type' => $type,
            'caption' => $validated['caption'] ?? null,
            'expires_at' => now()->addDay(),
        ]);

        // Send notifications to all users
        $allUsers = User::where('id', '!=', $request->user()->id)->get();
        Notification::send($allUsers, new StoryPostedNotification($story));

        return back()->with('status', 'Story posted successfully! Expires in 24 hours.');
    }

    /**
     * Delete a story
     */
    public function destroy(Story $story): RedirectResponse
    {
        $this->authorize('delete', $story);
        $story->delete();

        return back()->with('status', 'Story deleted!');
    }

    /**
     * Get user stories as JSON (for AJAX)
     */
    public function getUserStories($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json([], 404);
        }

        // Get active stories only (not expired)
        $stories = Story::where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'media_path' => $story->media_path,
                    'type' => $story->type,
                    'caption' => $story->caption,
                    'created_at' => $story->created_at->toIso8601String(),
                    'user_name' => $user->name,
                    'user_id' => $user->id,
                ];
            });

        return response()->json($stories);
    }
}