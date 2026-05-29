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
        Schema::create('sponsor_dogs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('raza');
            $table->integer('edad');
            $table->string('sexo');
            $table->string('foto')->nullable();
            $table->text('historia')->nullable();
            $table->text('necesidades')->nullable();
            $table->integer('meta_mensual')->default(0);
            $table->string('estado')->default('Disponible');
            $table->boolean('publicado')->default(false);
            $table->timestamps();
            
            $table->index('estado');
            $table->index('publicado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_dogs');
    }
};
