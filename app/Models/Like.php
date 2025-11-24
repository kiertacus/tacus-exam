<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'tweet_id',
    ];

    // Relationship: A like belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A like belongs to a Tweet
    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}