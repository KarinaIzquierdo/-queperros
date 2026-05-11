<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('mascotas')) {
            return;
        }

        Schema::table('mascotas', function (Blueprint $table) {
            // Agregar columna para relacionar con el dueño
            if (!Schema::hasColumn('mascotas', 'id_dueno')) {
                $table->unsignedBigInteger('id_dueno')->nullable()->after('id');
                $table->index('id_dueno');
            }

            // Agregar columna tipo si no existe
            if (!Schema::hasColumn('mascotas', 'tipo')) {
                $table->string('tipo')->nullable()->after('raza');
            }

            // Agregar columna servicio_requerido si no existe
            if (!Schema::hasColumn('mascotas', 'servicio_requerido')) {
                $table->string('servicio_requerido')->nullable();
            }

            // Corregir nombre de columna de foto si es necesario
            if (Schema::hasColumn('mascotas', 'foto_path') && !Schema::hasColumn('mascotas', 'foto')) {
                $table->renameColumn('foto_path', 'foto');
            }

            // Corregir nombre de columna de fecha de vacuna tos si es necesario
            if (Schema::hasColumn('mascotas', 'fecha_ultima_vacuna_tos') && !Schema::hasColumn('mascotas', 'fecha_vacuna_tos_perreras')) {
                $table->renameColumn('fecha_ultima_vacuna_tos', 'fecha_vacuna_tos_perreras');
            }

            // Corregir nombre de columna de info_adicional si es necesario
            if (Schema::hasColumn('mascotas', 'informacion_adicional') && !Schema::hasColumn('mascotas', 'info_adicional')) {
                $table->renameColumn('informacion_adicional', 'info_adicional');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('mascotas')) {
            return;
        }

        Schema::table('mascotas', function (Blueprint $table) {
            // Revertir cambios si es necesario
            if (Schema::hasColumn('mascotas', 'id_dueno')) {
                $table->dropIndex(['id_dueno']);
                $table->dropColumn('id_dueno');
            }
            if (Schema::hasColumn('mascotas', 'tipo')) {
                $table->dropColumn('tipo');
            }
            if (Schema::hasColumn('mascotas', 'servicio_requerido')) {
                $table->dropColumn('servicio_requerido');
            }
        });
    }
};
