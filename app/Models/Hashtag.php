<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hashtag extends Model
{
    protected $fillable = ['name', 'description', 'count'];

    /**
     * Get the tweets that use this hashtag.
     */
    public function tweets(): BelongsToMany
    {
        return $this->belongsToMany(Tweet::class, 'hashtag_tweet');
    }
}
