<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notifications:purge', function () {
    $this->info('Iniciando depuración manual de notificaciones...');
    
    $count1 = 0;
    if (Schema::hasTable('notificaciones')) {
        // Limpiar notificaciones de más de 3 días (leídas o no)
        $count1 = DB::table('notificaciones')
            ->where('created_at', '<', now()->subDays(3))
            ->delete();
    }

    $count2 = 0;
    if (Schema::hasTable('notifications')) {
        $count2 = DB::table('notifications')
            ->where('created_at', '<', now()->subDays(3))
            ->delete();
    }

    $this->info("¡Depuración completada! Se eliminaron " . ($count1 + $count2) . " notificaciones.");
})->purpose('Elimina notificaciones de más de 3 días');

// Tarea para depurar notificaciones (más de 3 días)
Schedule::call(function () {
    if (Schema::hasTable('notificaciones')) {
        DB::table('notificaciones')
            ->where('created_at', '<', now()->subDays(3))
            ->delete();
    }
    if (Schema::hasTable('notifications')) {
        DB::table('notifications')
            ->where('created_at', '<', now()->subDays(3))
            ->delete();
    }
})->daily();

// Tarea para depurar historial de reservas (Canceladas de más de 30 días)
Schedule::call(function () {
    if (Schema::hasTable('reservas')) {
        DB::table('reservas')
            ->whereIn('estado', ['Cancelada', 'Rechazada'])
            ->where('created_at', '<', now()->subDays(30))
            ->delete();
    }
})->monthly();
