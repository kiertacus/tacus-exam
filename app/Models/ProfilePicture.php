<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilePicture extends Model
{
    protected $fillable = ['user_id', 'path'];

    /**
     * Get the user that owns this profile picture
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
