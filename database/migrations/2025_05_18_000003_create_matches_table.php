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
            $table->foreignId('phase_id')->constrained('tournament_phases')->cascadeOnDelete();
            $table->string('home_team');
            $table->string('away_team');
            $table->string('home_code', 3)->nullable();
            $table->string('away_code', 3)->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('kick_off_at');
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->enum('status', ['pending', 'played', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['phase_id', 'kick_off_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
