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

    // Helper: Check if the current user has liked this tweet
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}