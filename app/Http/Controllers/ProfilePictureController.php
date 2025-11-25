<?php

namespace App\Http\Controllers;

use App\Models\ProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    /**
     * Store a newly uploaded profile picture.
     */
    public function store(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = $request->user();
        
        // Delete old profile picture if exists
        if ($user->profilePicture) {
            Storage::disk('profile_pictures')->delete($user->profilePicture->path);
            $user->profilePicture->delete();
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('', 'profile_pictures');

        ProfilePicture::create([
            'user_id' => $user->id,
            'path' => $path,
        ]);

        return back()->with('status', 'profile-picture-updated');
    }

    /**
     * Delete the user's profile picture.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        
        if ($user->profilePicture) {
            Storage::disk('profile_pictures')->delete($user->profilePicture->path);
            $user->profilePicture->delete();
        }

        return back()->with('status', 'profile-picture-deleted');
    }
}
