<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tweet;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Tweet $tweet): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:500'],
        ]);

        $comment = $tweet->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        // Create notification if commenter is not the tweet author
        if ($request->user()->id !== $tweet->user_id) {
            Notification::create([
                'user_id' => $tweet->user_id,
                'from_user_id' => $request->user()->id,
                'type' => 'comment',
                'tweet_id' => $tweet->id,
                'message' => $request->user()->name . ' commented on your post',
            ]);
        }

        return redirect()->route('tweets.index')->with('status', 'Comment added successfully!');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        
        $tweetId = $comment->tweet_id;
        $comment->delete();

        return redirect()->route('tweets.index')->with('status', 'Comment deleted successfully!');
    }
}
