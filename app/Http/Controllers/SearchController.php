<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Search for users and tweets
     */
    public function index(Request $request): View
    {
        $query = $request->input('q', '');
        $users = [];
        $tweets = [];

        if (strlen($query) >= 2) {
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(10)
                ->get();

            $tweets = Tweet::where('content', 'like', "%{$query}%")
                ->with('user', 'likes')
                ->latest()
                ->limit(15)
                ->get();
        }

        return view('search.results', [
            'query' => $query,
            'users' => $users,
            'tweets' => $tweets,
        ]);
    }
}
