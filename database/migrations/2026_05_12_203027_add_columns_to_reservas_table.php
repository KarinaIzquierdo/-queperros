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
        if (! Schema::hasTable('reservas')) {
            return;
        }

        Schema::table('reservas', function (Blueprint $table) {
            if (! Schema::hasColumn('reservas', 'id_mascota')) {
                $column = $table->integer('id_mascota');
                if (Schema::hasColumn('reservas', 'id')) {
                    $column->after('id');
                }
            }

            if (! Schema::hasColumn('reservas', 'id_empleado')) {
                $table->integer('id_empleado')->nullable();
            }

            if (! Schema::hasColumn('reservas', 'id_actividad')) {
                $table->integer('id_actividad')->nullable();
            }

            if (! Schema::hasColumn('reservas', 'fecha')) {
                $table->date('fecha')->nullable();
            }

            if (! Schema::hasColumn('reservas', 'estado')) {
                $table->enum('estado', ['Pendiente', 'Confirmada', 'Cancelada', 'Finalizada'])->default('Pendiente');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            //
        });
    }
};
