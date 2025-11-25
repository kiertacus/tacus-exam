<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Tweet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(): View
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with('tweet.user.profilePicture', 'tweet.media')
            ->latest()
            ->paginate(15);

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function store(Tweet $tweet): RedirectResponse
    {
        $user = auth()->user();

        // Check if already bookmarked
        if ($tweet->isBookmarkedBy($user)) {
            Bookmark::where('user_id', $user->id)
                ->where('tweet_id', $tweet->id)
                ->delete();
            return back()->with('success', 'Bookmark removed');
        }

        Bookmark::create([
            'user_id' => $user->id,
            'tweet_id' => $tweet->id,
        ]);

        return back()->with('success', 'Post bookmarked!');
    }

    public function destroy(Tweet $tweet): RedirectResponse
    {
        auth()->user()->bookmarks()
            ->where('tweet_id', $tweet->id)
            ->delete();

        return back()->with('success', 'Bookmark removed');
    }
}
