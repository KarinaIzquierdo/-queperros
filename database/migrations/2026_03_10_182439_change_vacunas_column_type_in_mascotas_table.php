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
        if (! Schema::hasTable('mascotas')) {
            return;
        }

        Schema::table('mascotas', function (Blueprint $table) {
            if (Schema::hasColumn('mascotas', 'vacunas')) {
                $table->text('vacunas')->nullable()->change();
            } else {
                $table->text('vacunas')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('mascotas') || ! Schema::hasColumn('mascotas', 'vacunas')) {
            return;
        }

        Schema::table('mascotas', function (Blueprint $table) {
            $table->enum('vacunas', ['Moquillo', 'Parvovirus', 'Hepatitis', 'Parainfluenza', 'Leptospira', 'Rabia', 'Multiple (DHPP)', 'Sextuple', 'Ninguna'])->nullable()->change();
        });
    }
};
