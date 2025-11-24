<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all notifications
     */
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->with('fromUser', 'tweet')
            ->latest()
            ->get();

        // Mark all as read
        auth()->user()->notifications()->update(['read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function read($id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->update(['read' => true]);

        if ($notification->tweet) {
            return redirect()->route('tweets.index');
        }

        return redirect()->route('profile.show', $notification->fromUser);
    }
}
