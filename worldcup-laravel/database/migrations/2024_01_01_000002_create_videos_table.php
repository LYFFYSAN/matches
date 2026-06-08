<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->string('title', 200);
            $table->string('embed_url', 500);
            $table->enum('platform', ['youtube', 'dailymotion', 'twitter', 'other'])->default('youtube');
            $table->enum('language', ['ar', 'en', 'fr', 'es', 'pt']);
            $table->integer('duration_s')->nullable();
            $table->timestamps();

            $table->index('match_id');
            $table->index('language');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
