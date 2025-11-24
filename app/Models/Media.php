<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['tweet_id', 'path', 'type'];

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
