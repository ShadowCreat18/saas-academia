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
            $table->string('opponent');
            $table->string('category')->nullable();
            $table->date('match_date');
            $table->time('match_time');
            $table->string('location')->nullable();
            $table->string('result')->nullable();
            $table->string('status')->default('Programado'); // Programado, Finalizado, Cancelado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
