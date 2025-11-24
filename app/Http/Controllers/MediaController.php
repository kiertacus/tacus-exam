<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Store a media file for a tweet
     */
    public function store(Request $request, Tweet $tweet)
    {
        $this->authorize('create', $tweet);

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
    public function destroy($mediaId)
    {
        $media = \App\Models\Media::find($mediaId);
        $tweet = $media->tweet;

        $this->authorize('delete', $tweet);

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
