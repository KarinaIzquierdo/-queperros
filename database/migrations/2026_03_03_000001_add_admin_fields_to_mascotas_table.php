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
            if (!Schema::hasColumn('mascotas', 'sexo')) {
                $table->string('sexo')->nullable();
            }
            if (!Schema::hasColumn('mascotas', 'nombre_tutor')) {
                $table->string('nombre_tutor')->nullable();
            }
            if (!Schema::hasColumn('mascotas', 'telefono')) {
                $table->string('telefono')->nullable();
            }
            if (!Schema::hasColumn('mascotas', 'estado_actual')) {
                $table->string('estado_actual')->nullable();
            }
            if (!Schema::hasColumn('mascotas', 'notas_adicionales')) {
                $table->string('notas_adicionales')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('mascotas')) {
            return;
        }

        Schema::table('mascotas', function (Blueprint $table) {
            if (Schema::hasColumn('mascotas', 'sexo')) {
                $table->dropColumn('sexo');
            }
            if (Schema::hasColumn('mascotas', 'nombre_tutor')) {
                $table->dropColumn('nombre_tutor');
            }
            if (Schema::hasColumn('mascotas', 'telefono')) {
                $table->dropColumn('telefono');
            }
            if (Schema::hasColumn('mascotas', 'estado_actual')) {
                $table->dropColumn('estado_actual');
            }
            if (Schema::hasColumn('mascotas', 'notas_adicionales')) {
                $table->dropColumn('notas_adicionales');
            }
        });
    }
};
