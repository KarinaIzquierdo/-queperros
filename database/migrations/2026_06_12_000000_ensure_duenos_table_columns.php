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
        // 1. Crear la tabla duenos si no existe
        if (!Schema::hasTable('duenos')) {
            Schema::create('duenos', function (Blueprint $table) {
                $table->unsignedBigInteger('id_dueno')->primary(); // El ID será el mismo del usuario
                $table->string('nombre')->nullable();
                $table->string('telefono', 60)->nullable();
                $table->string('documento', 80)->nullable();
                $table->string('direccion')->nullable();
                $table->string('ciudad', 120)->nullable();
                $table->date('fecha_nacimiento')->nullable();
                $table->timestamps();
            });
        } else {
            // 2. Si existe, asegurar que todas las columnas necesarias estén presentes
            Schema::table('duenos', function (Blueprint $table) {
                if (!Schema::hasColumn('duenos', 'nombre')) {
                    $table->string('nombre')->nullable()->after('id_dueno');
                }
                if (!Schema::hasColumn('duenos', 'telefono')) {
                    $table->string('telefono', 60)->nullable()->after('nombre');
                }
                if (!Schema::hasColumn('duenos', 'documento')) {
                    $table->string('documento', 80)->nullable()->after('telefono');
                }
                if (!Schema::hasColumn('duenos', 'direccion')) {
                    $table->string('direccion')->nullable()->after('documento');
                }
                if (!Schema::hasColumn('duenos', 'ciudad')) {
                    $table->string('ciudad', 120)->nullable()->after('direccion');
                }
                if (!Schema::hasColumn('duenos', 'fecha_nacimiento')) {
                    $table->date('fecha_nacimiento')->nullable()->after('ciudad');
                }
                
                // Asegurar timestamps si faltan
                if (!Schema::hasColumn('duenos', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('duenos', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminamos la tabla por seguridad si ya existía
    }
};
