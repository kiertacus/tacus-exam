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
use App\Http\Controllers\HashtagController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\ProfilePictureController;
use App\Http\Controllers\RetweetController;
use App\Http\Controllers\BookmarkController;
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

// Hashtag functionality
Route::get('/hashtag/{tag}', [HashtagController::class, 'show'])->name('hashtags.show');

// Routes protected by the 'auth' middleware (must be logged in)
Route::middleware('auth')->group(function () {
    // 1. Tweet Management Routes (Create, Edit, Delete)
    Route::resource('tweets', TweetController::class)
        ->only(['store', 'edit', 'update', 'destroy']);

    // 2. Like System Route
    // This route handles both liking and unliking by using the toggle method
    Route::post('/tweets/{tweet}/like', [LikeController::class, 'toggle'])->name('likes.toggle');

    // 2b. Retweet Routes
    Route::post('/tweets/{tweet}/retweet', [RetweetController::class, 'store'])->name('retweets.store');
    Route::delete('/tweets/{tweet}/retweet', [RetweetController::class, 'destroy'])->name('retweets.destroy');

    // 2c. Bookmark Routes
    Route::post('/tweets/{tweet}/bookmark', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/tweets/{tweet}/bookmark', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    // 2d. Comment Routes
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

    // 7. Story Routes
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::delete('/stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');

    // 8. Call Routes (Voice & Video)
    Route::post('/calls/initiate', [CallController::class, 'initiate'])->name('calls.initiate');
    Route::post('/calls/{message}/end', [CallController::class, 'end'])->name('calls.end');
    Route::post('/calls/{message}/decline', [CallController::class, 'decline'])->name('calls.decline');

    // 9. Profile Picture Routes
    Route::post('/profile-picture', [ProfilePictureController::class, 'store'])->name('profile-picture.store');
    Route::delete('/profile-picture', [ProfilePictureController::class, 'destroy'])->name('profile-picture.destroy');
});


// --- Standard Breeze/Auth Routes ---
// The profile update/password change routes provided by Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- API Routes for AJAX Requests ---
Route::get('/api/stories/{user}', [StoryController::class, 'getUserStories']);

require __DIR__.'/auth.php';
