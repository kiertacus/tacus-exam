<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HashtagController extends Controller
{
    /**
     * Show posts for a specific hashtag
     */
    public function show(string $tag): View
    {
        $hashtag = Hashtag::where('name', strtolower($tag))->firstOrFail();
        
        $tweets = $hashtag->tweets()
            ->with('user', 'media', 'comments.user')
            ->withCount('likes', 'comments')
            ->latest()
            ->get();
        
        return view('hashtags.show', compact('hashtag', 'tweets'));
    }
}
