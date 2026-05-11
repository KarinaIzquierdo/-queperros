<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mascotas')) {
            return;
        }

        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('raza');
            $table->string('edad')->nullable();
            $table->string('sexo')->nullable();
            $table->string('foto_path')->nullable();

            $table->text('vacunas')->nullable();
            $table->date('fecha_ultima_desparasitacion')->nullable();
            $table->date('fecha_ultima_vacuna_tos')->nullable();

            $table->string('nombre_tutor');
            $table->string('telefono')->nullable();

            $table->text('info_adicional')->nullable();

            $table->string('servicio_requerido')->nullable();
            $table->string('estado_actual')->nullable();
            $table->string('notas_adicionales')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
