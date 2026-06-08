<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('team1', 100);
            $table->string('team2', 100);
            $table->string('team1_flag', 500)->nullable();
            $table->string('team2_flag', 500)->nullable();
            $table->integer('score1')->nullable();
            $table->integer('score2')->nullable();
            $table->dateTime('match_date');
            $table->string('stage', 100);           // 'Group Stage', 'Quarter Final', etc.
            $table->string('group_name', 50)->nullable(); // 'Group A', null for knockouts
            $table->enum('status', ['upcoming', 'live', 'finished'])->default('upcoming');
            $table->timestamps();

            $table->index('status');
            $table->index('match_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
