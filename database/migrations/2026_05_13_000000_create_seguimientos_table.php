<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mascota');
            $table->unsignedBigInteger('id_entrenador')->nullable();
            $table->unsignedBigInteger('id_actividad')->nullable();
            $table->string('estado_animo')->nullable();
            $table->unsignedInteger('duracion')->nullable();
            $table->string('nivel_progreso')->nullable();
            $table->text('notas')->nullable();
            $table->text('mensaje_dueno')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
