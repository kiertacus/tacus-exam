<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
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
            $message = 'Tweet unliked.';
        } else {
            // If like doesn't exist, create it (Like)
            $user->likes()->create([
                'tweet_id' => $tweet->id,
            ]);
            $message = 'Tweet liked!';
        }

        // Redirect back to the page the user was on
        return back()->with('status', $message);
    }
}
