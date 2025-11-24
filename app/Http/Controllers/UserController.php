<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(User $user): View
    {
        $tweets = $user->tweets()
            ->with('user', 'media')
            ->withCount('likes')
            ->latest()
            ->get();

        return view('profile.show', compact('user', 'tweets'));
    }
}
