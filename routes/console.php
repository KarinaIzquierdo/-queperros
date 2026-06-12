<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tarea para depurar notificaciones antiguas (más de 30 días si están leídas)
Schedule::call(function () {
    // Depurar tabla 'notificaciones'
    if (Schema::hasTable('notificaciones')) {
        DB::table('notificaciones')
            ->whereNotNull('leida_en')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();
    }

    // Depurar tabla 'notifications' (la otra versión que tienes)
    if (Schema::hasTable('notifications')) {
        DB::table('notifications')
            ->where('leido', true)
            ->where('created_at', '<', now()->subDays(30))
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
