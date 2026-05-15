<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 12)->unique();
            $table->string('description')->nullable();
            $table->timestamps();

            // Un usuario no puede crear 2 comunidades con el mismo nombre
            $table->unique(['creator_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
