<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('subdomain')->unique();
            $table->string('academy_name');
            $table->string('owner_name');
            $table->string('owner_email')->unique();
            $table->enum('plan', ['basico', 'elite', 'premium'])->default('basico');
            $table->enum('status', ['trial', 'active', 'suspended', 'cancelled'])->default('trial');
            // Credenciales de la base de datos del tenant (encriptadas)
            $table->string('db_name')->nullable();
            $table->string('db_user')->nullable();
            $table->text('db_password')->nullable(); // encriptada con encrypt()
            $table->string('db_host')->default('127.0.0.1');
            // Stripe
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            // Fechas
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
