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
        Schema::table('reservas', function (Blueprint $table) {
            if (!Schema::hasColumn('reservas', 'fecha_evaluacion')) {
                $table->date('fecha_evaluacion')->nullable()->after('fecha');
            }
            if (!Schema::hasColumn('reservas', 'hora_evaluacion')) {
                $table->time('hora_evaluacion')->nullable()->after('fecha_evaluacion');
            }
            if (!Schema::hasColumn('reservas', 'duracion')) {
                $table->integer('duracion')->nullable()->after('precio');
            }
            if (!Schema::hasColumn('reservas', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('duracion');
            }
            if (!Schema::hasColumn('reservas', 'cliente_aceptado')) {
                $table->boolean('cliente_aceptado')->nullable()->after('observaciones');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            if (Schema::hasColumn('reservas', 'fecha_evaluacion')) {
                $table->dropColumn('fecha_evaluacion');
            }
            if (Schema::hasColumn('reservas', 'hora_evaluacion')) {
                $table->dropColumn('hora_evaluacion');
            }
            if (Schema::hasColumn('reservas', 'duracion')) {
                $table->dropColumn('duracion');
            }
            if (Schema::hasColumn('reservas', 'observaciones')) {
                $table->dropColumn('observaciones');
            }
            if (Schema::hasColumn('reservas', 'cliente_aceptado')) {
                $table->dropColumn('cliente_aceptado');
            }
        });
    }
};
