<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Store a media file for a tweet
     */
    public function store(Request $request, Tweet $tweet)
    {
        // Check authorization
        if (auth()->user()->id !== $tweet->user_id) {
            return back()->with('error', 'Unauthorized');
        }

        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200',
        ]);

        $file = $request->file('media');
        $type = $this->getMediaType($file->getMimeType());
        $path = $file->store('tweets', 'public');

        $tweet->media()->create([
            'path' => $path,
            'type' => $type,
        ]);

        return back()->with('success', 'Media uploaded successfully');
    }

    /**
     * Delete a media file
     */
    public function destroy(Media $media)
    {
        $tweet = $media->tweet;

        // Check authorization
        if (auth()->user()->id !== $tweet->user_id) {
            return back()->with('error', 'Unauthorized');
        }

        // Delete file from storage
        Storage::disk('public')->delete($media->path);

        $media->delete();

        return back()->with('success', 'Media deleted successfully');
    }

    /**
     * Determine media type from MIME type
     */
    private function getMediaType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        return 'image';
    }
}
