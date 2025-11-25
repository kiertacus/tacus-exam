<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = [
        'content',
        'user_id',
    ];

    // Relationship: A tweet belongs to a User (the author)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A tweet can have many Likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Relationship: A tweet can have many media attachments
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    // Relationship: A tweet can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relationship: A tweet can have many hashtags
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'hashtag_tweet');
    }

    // Relationship: A tweet can have many retweets
    public function retweets()
    {
        return $this->hasMany(Retweet::class);
    }

    // Relationship: A tweet can have many bookmarks
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // Helper: Check if the current user has liked this tweet
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    // Helper: Check if the current user has retweeted this tweet
    public function isRetweetedBy(User $user)
    {
        return $this->retweets()->where('user_id', $user->id)->exists();
    }

    // Helper: Check if the current user has bookmarked this tweet
    public function isBookmarkedBy(User $user)
    {
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }
}