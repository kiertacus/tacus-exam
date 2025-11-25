<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Story extends Model
{
    protected $fillable = ['user_id', 'media_path', 'type', 'caption', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime'];

    /**
     * Get the user that owns this story
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if story has expired
     */
    public function hasExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }
}
