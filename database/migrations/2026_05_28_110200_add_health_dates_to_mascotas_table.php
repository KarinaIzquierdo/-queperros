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
            if (!Schema::hasColumn('mascotas', 'fecha_ultima_desparasitacion')) {
                $table->date('fecha_ultima_desparasitacion')->nullable()->after('vacunas');
            }

            if (!Schema::hasColumn('mascotas', 'fecha_vacuna_tos_perreras')) {
                $table->date('fecha_vacuna_tos_perreras')->nullable()->after('fecha_ultima_desparasitacion');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('mascotas')) {
            return;
        }

        Schema::table('mascotas', function (Blueprint $table) {
            if (Schema::hasColumn('mascotas', 'fecha_vacuna_tos_perreras')) {
                $table->dropColumn('fecha_vacuna_tos_perreras');
            }

            if (Schema::hasColumn('mascotas', 'fecha_ultima_desparasitacion')) {
                $table->dropColumn('fecha_ultima_desparasitacion');
            }
        });
    }
};
