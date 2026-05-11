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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_negocio')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email')->nullable();
            $table->time('hora_apertura')->nullable();
            $table->time('hora_cierre')->nullable();
            $table->tinyInteger('atiende_fines_semana')->default(0);
            $table->tinyInteger('notificaciones_email')->default(1);
            $table->tinyInteger('notificaciones_sms')->default(0);
            $table->tinyInteger('recordatorio_citas')->default(1);
            $table->tinyInteger('autenticacion_dos_factores')->default(0);
            $table->integer('cierre_sesion_minutos')->default(120);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
