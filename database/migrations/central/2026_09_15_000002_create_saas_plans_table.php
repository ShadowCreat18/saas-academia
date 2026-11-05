<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();       // basico, elite, premium
            $table->string('name');                 // Básico, Elite, Premium
            $table->integer('price_monthly');       // en centavos MXN (39900 = $399)
            $table->integer('price_annual');
            $table->string('stripe_price_monthly_id')->nullable(); // price_test_xxx
            $table->string('stripe_price_annual_id')->nullable();
            $table->integer('max_categories');
            $table->integer('max_players');
            $table->integer('max_seasons');
            $table->integer('max_admins');
            $table->boolean('has_stripe_payments')->default(false);
            $table->boolean('has_wallet')->default(false);
            $table->boolean('has_games')->default(false);
            $table->boolean('has_pdf')->default(false);
            $table->boolean('has_csv_import')->default(false);
            $table->boolean('has_custom_domain')->default(false);
            $table->boolean('has_full_customization')->default(false);
            $table->boolean('has_whatsapp')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_plans');
    }
};
