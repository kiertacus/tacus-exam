<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle the like/unlike action.
     */
    public function toggle(Tweet $tweet): RedirectResponse
    {
        $user = auth()->user();

        // Find an existing like by this user on this tweet
        $like = $user->likes()->where('tweet_id', $tweet->id)->first();

        if ($like) {
            // If like exists, remove it (Unlike)
            $like->delete();
            // Remove notification
            Notification::where('from_user_id', $user->id)
                ->where('tweet_id', $tweet->id)
                ->where('type', 'like')
                ->delete();
            $message = 'Tweet unliked.';
        } else {
            // If like doesn't exist, create it (Like)
            $user->likes()->create([
                'tweet_id' => $tweet->id,
            ]);

            // Create notification if liker is not the tweet author
            if ($user->id !== $tweet->user_id) {
                Notification::create([
                    'user_id' => $tweet->user_id,
                    'from_user_id' => $user->id,
                    'type' => 'like',
                    'tweet_id' => $tweet->id,
                    'message' => $user->name . ' liked your post',
                ]);
            }

            $message = 'Tweet liked!';
        }

        // Redirect back to the page the user was on
        return back()->with('status', $message);
    }
}
