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
        $count1 = DB::table('notificaciones')
            ->whereNotNull('leida_en')
            ->where('created_at', '<', now()->subDays(10))
            ->delete();
    }

    $count2 = 0;
    if (Schema::hasTable('notifications')) {
        $count2 = DB::table('notifications')
            ->where('leido', true)
            ->where('created_at', '<', now()->subDays(10))
            ->delete();
    }

    $this->info("¡Depuración completada! Se eliminaron " . ($count1 + $count2) . " notificaciones antiguas.");
})->purpose('Elimina notificaciones leídas de más de 10 días');

// Tarea para depurar notificaciones antiguas (más de 10 días si están leídas)
Schedule::call(function () {
    // Depurar tabla 'notificaciones'
    if (Schema::hasTable('notificaciones')) {
        DB::table('notificaciones')
            ->whereNotNull('leida_en')
            ->where('created_at', '<', now()->subDays(10))
            ->delete();
    }

    // Depurar tabla 'notifications' (la otra versión que tienes)
    if (Schema::hasTable('notifications')) {
        DB::table('notifications')
            ->where('leido', true)
            ->where('created_at', '<', now()->subDays(10))
            ->delete();
    }

    \Illuminate\Support\Facades\Log::info('Depuración de notificaciones completada.');
})->daily();

// Tarea para depurar historial de reservas antiguas (Canceladas o Finalizadas de más de 6 meses)
Schedule::call(function () {
    if (Schema::hasTable('reservas')) {
        DB::table('reservas')
            ->whereIn('estado', ['Cancelada', 'Finalizada', 'Rechazada'])
            ->where('created_at', '<', now()->subMonths(6))
            ->delete();
    }
    
    \Illuminate\Support\Facades\Log::info('Depuración de historial de reservas completada.');
})->monthly();
