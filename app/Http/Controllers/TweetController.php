<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TweetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource (The Homepage Feed).
     */
    public function index(): View
    {
        $tweets = Tweet::with('user', 'media')
            ->withCount('likes')
            ->latest()
            ->get();

        return view('tweets.index', compact('tweets'));
    }

    /**
     * Store a newly created tweet.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:280'],
        ]);

        $request->user()->tweets()->create($validated);

        return redirect()->route('tweets.index')->with('status', 'Tweet created successfully!');
    }

    /**
     * Show the form for editing the specified tweet.
     */
    public function edit(Tweet $tweet): View
    {
        $this->authorize('update', $tweet);

        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified tweet in storage.
     */
    public function update(Request $request, Tweet $tweet): RedirectResponse
    {
        $this->authorize('update', $tweet);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:280'],
        ]);

        $tweet->update($validated);

        return redirect()->route('tweets.index')->with('status', 'Tweet updated successfully!');
    }

    /**
     * Remove the specified tweet from storage.
     */
    public function destroy(Tweet $tweet): RedirectResponse
    {
        $this->authorize('delete', $tweet);

        $tweet->delete();

        return redirect()->route('tweets.index')->with('status', 'Tweet deleted successfully!');
    }
}