<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->integer('count')->default(0);
            $table->timestamps();
        });

        // Create pivot table for tweets and hashtags
        Schema::create('hashtag_tweet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tweet_id')->constrained()->onDelete('cascade');
            $table->foreignId('hashtag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['tweet_id', 'hashtag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hashtag_tweet');
        Schema::dropIfExists('hashtags');
    }
};
