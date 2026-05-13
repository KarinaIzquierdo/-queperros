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
            $table->integer('id_mascota')->after('id');
            $table->integer('id_empleado')->nullable()->after('id_mascota');
            $table->integer('id_actividad')->nullable()->after('id_empleado');
            $table->date('fecha')->nullable()->after('id_actividad');
            $table->enum('estado', ['Pendiente', 'Confirmada', 'Cancelada', 'Finalizada'])->default('Pendiente')->after('fecha');
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
