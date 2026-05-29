<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('empleados')) {
            return;
        }

        Schema::table('empleados', function (Blueprint $table) {
            if (!Schema::hasColumn('empleados', 'telefono')) {
                $table->string('telefono', 60)->nullable()->after('turno');
            }

            if (!Schema::hasColumn('empleados', 'especialidad')) {
                $table->string('especialidad')->nullable()->after('telefono');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('empleados')) {
            return;
        }

        Schema::table('empleados', function (Blueprint $table) {
            if (Schema::hasColumn('empleados', 'especialidad')) {
                $table->dropColumn('especialidad');
            }

            if (Schema::hasColumn('empleados', 'telefono')) {
                $table->dropColumn('telefono');
            }
        });
    }
};
