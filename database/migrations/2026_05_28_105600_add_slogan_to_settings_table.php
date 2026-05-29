<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('settings') || Schema::hasColumn('settings', 'slogan')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->string('slogan')->nullable()->after('nombre_negocio');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('settings') || !Schema::hasColumn('settings', 'slogan')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('slogan');
        });
    }
};
