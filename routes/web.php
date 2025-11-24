<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Core Application Routes ---

// The Homepage (Global Feed) - Accessible to all (even guests, though actions are protected)
Route::get('/', [TweetController::class, 'index'])->name('tweets.index');

// Search functionality
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Routes protected by the 'auth' middleware (must be logged in)
Route::middleware('auth')->group(function () {
    // 1. Tweet Management Routes (Create, Edit, Delete)
    Route::resource('tweets', TweetController::class)
        ->only(['store', 'edit', 'update', 'destroy']);

    // 2. Like System Route
    // This route handles both liking and unliking by using the toggle method
    Route::post('/tweets/{tweet}/like', [LikeController::class, 'toggle'])->name('likes.toggle');

    // 2b. Comment Routes
    Route::post('/tweets/{tweet}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // 3. User Profile Route (View your own profile, can be extended for others)
    Route::get('/profile/{user}', [UserController::class, 'show'])->name('profile.show');

    // 4. Messaging Routes
    Route::get('/messages', [MessageController::class, 'conversations'])->name('messages.conversations');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    // 5. Follow/Unfollow Routes
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('follow.destroy');

    // 5b. Notifications Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');

    // 6. Media Upload Routes
    Route::post('/tweets/{tweet}/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
});


// --- Standard Breeze/Auth Routes ---
// The profile update/password change routes provided by Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
