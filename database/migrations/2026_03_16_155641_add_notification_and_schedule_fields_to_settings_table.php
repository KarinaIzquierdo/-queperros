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
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'notificaciones_push')) {
                $table->tinyInteger('notificaciones_push')->default(0)->after('notificaciones_sms');
            }

            if (!Schema::hasColumn('settings', 'evento_nuevos_usuarios')) {
                $table->tinyInteger('evento_nuevos_usuarios')->default(1)->after('notificaciones_push');
            }

            if (!Schema::hasColumn('settings', 'evento_citas')) {
                $table->tinyInteger('evento_citas')->default(1)->after('evento_nuevos_usuarios');
            }

            if (!Schema::hasColumn('settings', 'evento_alertas_sistema')) {
                $table->tinyInteger('evento_alertas_sistema')->default(0)->after('evento_citas');
            }

            if (!Schema::hasColumn('settings', 'lunes_activo')) {
                $table->tinyInteger('lunes_activo')->default(1);
                $table->time('lunes_inicio')->default('08:00:00');
                $table->time('lunes_fin')->default('18:00:00');
            }

            if (!Schema::hasColumn('settings', 'martes_activo')) {
                $table->tinyInteger('martes_activo')->default(1);
                $table->time('martes_inicio')->default('08:00:00');
                $table->time('martes_fin')->default('18:00:00');
            }

            if (!Schema::hasColumn('settings', 'miercoles_activo')) {
                $table->tinyInteger('miercoles_activo')->default(1);
                $table->time('miercoles_inicio')->default('08:00:00');
                $table->time('miercoles_fin')->default('18:00:00');
            }

            if (!Schema::hasColumn('settings', 'jueves_activo')) {
                $table->tinyInteger('jueves_activo')->default(1);
                $table->time('jueves_inicio')->default('08:00:00');
                $table->time('jueves_fin')->default('18:00:00');
            }

            if (!Schema::hasColumn('settings', 'viernes_activo')) {
                $table->tinyInteger('viernes_activo')->default(1);
                $table->time('viernes_inicio')->default('08:00:00');
                $table->time('viernes_fin')->default('18:00:00');
            }

            if (!Schema::hasColumn('settings', 'sabado_activo')) {
                $table->tinyInteger('sabado_activo')->default(1);
                $table->time('sabado_inicio')->default('09:00:00');
                $table->time('sabado_fin')->default('14:00:00');
            }

            if (!Schema::hasColumn('settings', 'domingo_activo')) {
                $table->tinyInteger('domingo_activo')->default(0);
                $table->time('domingo_inicio')->nullable();
                $table->time('domingo_fin')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'notificaciones_push',
                'evento_nuevos_usuarios',
                'evento_citas',
                'evento_alertas_sistema',
                'lunes_activo', 'lunes_inicio', 'lunes_fin',
                'martes_activo', 'martes_inicio', 'martes_fin',
                'miercoles_activo', 'miercoles_inicio', 'miercoles_fin',
                'jueves_activo', 'jueves_inicio', 'jueves_fin',
                'viernes_activo', 'viernes_inicio', 'viernes_fin',
                'sabado_activo', 'sabado_inicio', 'sabado_fin',
                'domingo_activo', 'domingo_inicio', 'domingo_fin',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
