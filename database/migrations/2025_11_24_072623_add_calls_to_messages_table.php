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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('call_type')->nullable(); // 'voice', 'video', null for regular message
            $table->integer('call_duration')->nullable(); // duration in seconds
            $table->string('call_status')->nullable(); // 'completed', 'missed', 'declined'
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('call_type');
            $table->dropColumn('call_duration');
            $table->dropColumn('call_status');
        });
    }
};
