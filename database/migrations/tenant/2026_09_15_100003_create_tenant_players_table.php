<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('curp')->nullable();
            $table->string('jersey_number')->nullable();
            $table->date('birth_date');
            $table->string('position')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('contact_info')->nullable();
            $table->enum('scholarship_type', ['regular', 'sibling_discount', 'full_scholarship', 'custom_support'])->default('regular');
            $table->decimal('scholarship_value', 10, 2)->nullable();
            $table->string('category')->nullable();
            $table->string('secondary_category')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->year('charges_generated_year')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla pivote jugador ↔ tutor/padre
        Schema::create('player_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Tabla pivote jugador ↔ categoría (con temporada)
        Schema::create('category_player', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('season_id')->nullable()->constrained('seasons')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_player');
        Schema::dropIfExists('player_user');
        Schema::dropIfExists('players');
    }
};
