<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = ['tweet_id', 'user_id', 'content'];

    /**
     * Get the tweet that this comment belongs to.
     */
    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweet::class);
    }

    /**
     * Get the user that made this comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}