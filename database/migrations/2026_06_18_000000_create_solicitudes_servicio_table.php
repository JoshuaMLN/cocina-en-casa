<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_servicio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('telefono', 16)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('mensaje')->nullable();
            $table->string('estado', 20)->default('nuevo')->index();
            $table->timestamp('leido_at')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_servicio');
    }
};
