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

    $count3 = 0;
    if (Schema::hasTable('sponsorships')) {
        $count3 = DB::table('sponsorships')
            ->where('estado', 'Pendiente')
            ->where('created_at', '<', now()->subDays(7))
            ->delete();
    }

    $count4 = 0;
    if (Schema::hasTable('service_approvals')) {
        $count4 = DB::table('service_approvals')
            ->where(function($query) {
                $query->whereIn('estado', ['rechazado', 'pagado'])
                      ->where('created_at', '<', now()->subDays(3));
            })
            ->orWhere(function($query) {
                $query->where('estado', 'aprobado')
                      ->where('created_at', '<', now()->subDays(5));
            })
            ->delete();
    }

    $this->info("¡Depuración completada! Se eliminaron " . ($count1 + $count2) . " notificaciones, " . $count3 . " apadrinamientos y " . $count4 . " aprobaciones antiguas.");
})->purpose('Elimina notificaciones, apadrinamientos y aprobaciones antiguas');

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

// Tarea para depurar historial de reservas (Canceladas de más de 7 días)
Schedule::call(function () {
    if (Schema::hasTable('reservas')) {
        DB::table('reservas')
            ->whereIn('estado', ['Cancelada', 'Rechazada'])
            ->where('created_at', '<', now()->subDays(7))
            ->delete();
    }
})->daily();

// Tarea para depurar apadrinamientos pendientes de más de 7 días
Schedule::call(function () {
    if (Schema::hasTable('sponsorships')) {
        DB::table('sponsorships')
            ->where('estado', 'Pendiente')
            ->where('created_at', '<', now()->subDays(7))
            ->delete();
    }
})->daily();

// Tarea para depurar aprobaciones de servicios antiguas
Schedule::call(function () {
    if (Schema::hasTable('service_approvals')) {
        // Borrar rechazados y pagados de más de 3 días
        DB::table('service_approvals')
            ->whereIn('estado', ['rechazado', 'pagado'])
            ->where('created_at', '<', now()->subDays(3))
            ->delete();
            
        // Borrar aprobados no pagados de más de 5 días
        DB::table('service_approvals')
            ->where('estado', 'aprobado')
            ->where('created_at', '<', now()->subDays(5))
            ->delete();
    }
})->daily();
