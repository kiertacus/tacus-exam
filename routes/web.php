<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Core Application Routes ---

// The Homepage (Global Feed) - Accessible to all (even guests, though actions are protected)
Route::get('/', [TweetController::class, 'index'])->name('tweets.index');


// Routes protected by the 'auth' middleware (must be logged in)
Route::middleware('auth')->group(function () {
    // 1. Tweet Management Routes (Create, Edit, Delete)
    Route::resource('tweets', TweetController::class)
        ->only(['store', 'edit', 'update', 'destroy']);

    // 2. Like System Route
    // This route handles both liking and unliking by using the toggle method
    Route::post('/tweets/{tweet}/like', [LikeController::class, 'toggle'])->name('likes.toggle');

    // 3. User Profile Route (View your own profile, can be extended for others)
    Route::get('/profile/{user}', [UserController::class, 'show'])->name('profile.show');
});


// --- Standard Breeze/Auth Routes ---
// The profile update/password change routes provided by Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';