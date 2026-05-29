<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_usuario'); // Cliente que solicita
            $table->unsignedInteger('id_mascota');
            $table->unsignedBigInteger('id_servicio');
            $table->date('fecha_solicitada');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'pagado'])->default('pendiente');
            $table->text('notas_admin')->nullable();
            $table->text('notas_cliente')->nullable();
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_approvals');
    }
};
