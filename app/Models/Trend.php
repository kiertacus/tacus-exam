<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trend extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'count', 'category'];

    public static function updateTrends()
    {
        // Get top hashtags from the database
        $trends = \DB::table('hashtag_tweet')
            ->groupBy('hashtag_id')
            ->selectRaw('hashtag_id, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return $trends;
    }
}
