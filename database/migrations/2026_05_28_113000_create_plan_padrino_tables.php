<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sponsor_dogs')) {
            Schema::create('sponsor_dogs', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('raza')->nullable();
                $table->unsignedTinyInteger('edad')->nullable();
                $table->string('sexo')->nullable();
                $table->string('foto')->nullable();
                $table->text('historia')->nullable();
                $table->string('necesidades')->nullable();
                $table->unsignedInteger('meta_mensual')->nullable();
                $table->string('estado')->default('Disponible');
                $table->boolean('publicado')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sponsorships')) {
            Schema::create('sponsorships', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('sponsor_dog_id');
                $table->string('plan');
                $table->unsignedInteger('monto_mensual');
                $table->string('estado')->default('Pendiente');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorships');
        Schema::dropIfExists('sponsor_dogs');
    }
};
