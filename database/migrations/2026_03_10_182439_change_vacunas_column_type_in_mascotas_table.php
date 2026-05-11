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
        Schema::table('mascotas', function (Blueprint $table) {
            $table->text('vacunas')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->enum('vacunas', ['Moquillo', 'Parvovirus', 'Hepatitis', 'Parainfluenza', 'Leptospira', 'Rabia', 'Multiple (DHPP)', 'Sextuple', 'Ninguna'])->nullable()->change();
        });
    }
};
