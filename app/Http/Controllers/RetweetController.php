<?php

namespace App\Http\Controllers;

use App\Models\Retweet;
use App\Models\Tweet;
use Illuminate\Http\RedirectResponse;

class RetweetController extends Controller
{
    public function store(Tweet $tweet): RedirectResponse
    {
        $user = auth()->user();

        // Prevent user from retweeting their own tweet
        if ($tweet->user_id === $user->id) {
            return back()->with('error', 'You cannot retweet your own post');
        }

        // Check if already retweeted
        if ($tweet->isRetweetedBy($user)) {
            Retweet::where('user_id', $user->id)
                ->where('tweet_id', $tweet->id)
                ->delete();
            return back()->with('success', 'Retweet removed');
        }

        Retweet::create([
            'user_id' => $user->id,
            'tweet_id' => $tweet->id,
        ]);

        return back()->with('success', 'Post retweeted!');
    }

    public function destroy(Tweet $tweet): RedirectResponse
    {
        auth()->user()->retweets()
            ->where('tweet_id', $tweet->id)
            ->delete();

        return back()->with('success', 'Retweet removed');
    }
}
