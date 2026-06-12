<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminPetController;
use App\Http\Controllers\AdminSponsorDogController;
use App\Http\Controllers\OwnerPetController;
use App\Http\Controllers\OwnerServiceController;
use App\Http\Controllers\OwnerReservaController;
use App\Http\Controllers\OwnerModulesController;
use App\Http\Controllers\CaregiverDashboardController;
use App\Http\Controllers\TrainerDashboardController;
use App\Http\Controllers\TrainerModulesController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    try {
        $sponsorDogs = \App\Models\SponsorDog::query()
            ->where('publicado', true)
            ->where('estado', 'Disponible')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
    } catch (\Exception $e) {
        $sponsorDogs = collect();
    }
    return view('welcome', ['sponsorDogs' => $sponsorDogs]);
});

Route::get('/galeria', [\App\Http\Controllers\AdminGalleryController::class, 'publicGallery'])->name('galeria');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/dashboard', [ClientDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/dashboard/mis-perros', [OwnerPetController::class, 'index'])
    ->middleware('auth')
    ->name('owner.pets');

Route::post('/dashboard/mis-perros', [OwnerPetController::class, 'store'])
    ->middleware('auth')
    ->name('owner.pets.store');

Route::get('/dashboard/mis-perros/{mascota}', [OwnerPetController::class, 'show'])
    ->middleware('auth')
    ->name('owner.pets.show');

Route::get('/dashboard/mis-perros/{mascota}/edit', [OwnerPetController::class, 'edit'])
    ->middleware('auth')
    ->name('owner.pets.edit');

Route::put('/dashboard/mis-perros/{mascota}', [OwnerPetController::class, 'update'])
    ->middleware('auth')
    ->name('owner.pets.update');

Route::delete('/dashboard/mis-perros/{mascota}', [OwnerPetController::class, 'destroy'])
    ->middleware('auth')
    ->name('owner.pets.destroy');

Route::get('/dashboard/servicios', [OwnerServiceController::class, 'index'])
    ->middleware('auth')
    ->name('owner.services');

Route::post('/dashboard/reservas', [OwnerReservaController::class, 'store'])
    ->middleware('auth')
    ->name('owner.reservas.store');

Route::put('/dashboard/reservas/{reserva}', [OwnerReservaController::class, 'update'])
    ->middleware('auth')
    ->name('owner.reservas.update');

Route::post('/dashboard/reservas/{reserva}/cancel', [OwnerReservaController::class, 'cancel'])
    ->middleware('auth')
    ->name('owner.reservas.cancel');

Route::post('/dashboard/reservas/{reserva}/aceptar-cotizacion', [OwnerReservaController::class, 'aceptarCotizacion'])
    ->middleware('auth')
    ->name('owner.reservas.aceptar-cotizacion');

Route::post('/dashboard/reservas/{reserva}/rechazar-cotizacion', [OwnerReservaController::class, 'rechazarCotizacion'])
    ->middleware('auth')
    ->name('owner.reservas.rechazar-cotizacion');

Route::get('/dashboard/reservas', [OwnerModulesController::class, 'reservas'])
    ->middleware('auth')
    ->name('owner.reservas');

Route::get('/dashboard/seguimiento', [OwnerModulesController::class, 'seguimiento'])
    ->middleware('auth')
    ->name('owner.seguimiento');

Route::get('/dashboard/pagos', [OwnerModulesController::class, 'pagos'])
    ->middleware('auth')
    ->name('owner.pagos');

Route::post('/dashboard/pagos/reservas/{reserva}', [OwnerModulesController::class, 'pagarReserva'])
    ->middleware('auth')
    ->name('owner.pagos.reservas.pagar');

Route::get('/dashboard/plan-padrino', [OwnerModulesController::class, 'planPadrino'])
    ->middleware('auth')
    ->name('owner.planpadrino');

Route::post('/dashboard/plan-padrino/{dog}/apadrinar', [OwnerModulesController::class, 'storePadrinazgo'])
    ->middleware('auth')
    ->name('owner.planpadrino.store');

Route::get('/dashboard/mi-perfil', [OwnerModulesController::class, 'perfil'])
    ->middleware('auth')
    ->name('owner.perfil');

Route::post('/dashboard/mi-perfil', [OwnerModulesController::class, 'updatePerfil'])
    ->middleware('auth')
    ->name('owner.perfil.update');

Route::post('/dashboard/mi-perfil/password', [OwnerModulesController::class, 'updatePassword'])
    ->middleware('auth')
    ->name('owner.perfil.password');

Route::get('/dashboard/chat-entrenador', [OwnerModulesController::class, 'chat'])
    ->middleware('auth')
    ->name('owner.chat');

Route::post('/dashboard/chat-entrenador', [OwnerModulesController::class, 'sendMessage'])
    ->middleware('auth')
    ->name('owner.chat.send');

Route::get('/dashboard/notificaciones', [OwnerNotificationController::class, 'index'])
    ->middleware('auth')
    ->name('owner.notificaciones');

Route::post('/dashboard/notificaciones/{id}/mark-read', [OwnerNotificationController::class, 'markAsRead'])
    ->middleware('auth')
    ->name('owner.notifications.markRead');

Route::post('/dashboard/notificaciones/mark-all-read', [OwnerNotificationController::class, 'markAllAsRead'])
    ->middleware('auth')
    ->name('owner.notifications.markAllRead');

Route::get('/dashboard/galeria', [OwnerModulesController::class, 'galeria'])
    ->middleware('auth')
    ->name('owner.galeria');

Route::post('/dashboard/galeria/upload', [OwnerModulesController::class, 'uploadGaleria'])
    ->middleware('auth')
    ->name('owner.galeria.upload');

// Mis Servicios
Route::get('/dashboard/mis-servicios', [OwnerServiceController::class, 'myServices'])
    ->middleware('auth')
    ->name('owner.services.my');

Route::post('/dashboard/servicios/{approval}/pagar', [OwnerServiceController::class, 'processPayment'])
    ->middleware('auth')
    ->name('owner.services.payment');

Route::delete('/dashboard/galeria/{photo}', [OwnerModulesController::class, 'destroyPhoto'])
    ->middleware('auth')
    ->name('owner.galeria.destroy');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/notificaciones', [AdminDashboardController::class, 'notificaciones'])
        ->name('admin.notificaciones');
    Route::post('/admin/notificaciones/{id}/mark-read', [AdminDashboardController::class, 'markNotificationAsRead'])
        ->name('admin.notifications.markRead');
    Route::post('/admin/notificaciones/mark-all-read', [AdminDashboardController::class, 'markAllNotificationsAsRead'])
        ->name('admin.notifications.markAllRead');

    Route::get('/admin/users', [AdminUserController::class, 'index'])
        ->name('admin.users');

    Route::get('/admin/services', [AdminServiceController::class, 'index'])
        ->name('admin.services');

    Route::get('/admin/pets', [AdminPetController::class, 'index'])
        ->name('admin.pets');

    Route::get('/admin/plan-padrino', [AdminSponsorDogController::class, 'index'])
        ->name('admin.planpadrino');

    Route::post('/admin/plan-padrino', [AdminSponsorDogController::class, 'store'])
        ->name('admin.planpadrino.store');

    Route::put('/admin/plan-padrino/{dog}', [AdminSponsorDogController::class, 'update'])
        ->name('admin.planpadrino.update');

    Route::delete('/admin/plan-padrino/{dog}', [AdminSponsorDogController::class, 'destroy'])
        ->name('admin.planpadrino.destroy');

    Route::post('/admin/pets', [AdminPetController::class, 'store'])
        ->name('admin.pets.store');

    Route::put('/admin/pets/{mascota}', [AdminPetController::class, 'update'])
        ->name('admin.pets.update');

    Route::delete('/admin/pets/{mascota}', [AdminPetController::class, 'destroy'])
        ->name('admin.pets.destroy');

    Route::post('/admin/services', [AdminServiceController::class, 'store'])
        ->name('admin.services.store');

    Route::put('/admin/services/{servicio}', [AdminServiceController::class, 'update'])
        ->name('admin.services.update');

    Route::delete('/admin/services/{servicio}', [AdminServiceController::class, 'destroy'])
        ->name('admin.services.destroy');

    Route::patch('/admin/services/{servicio}/toggle-active', [AdminServiceController::class, 'toggleActive'])
        ->name('admin.services.toggleActive');

    Route::post('/admin/users', [AdminUserController::class, 'store'])
        ->name('admin.users.store');

    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])
        ->name('admin.users.update');

    Route::post('/admin/users/assign-role', [AdminUserController::class, 'assignRole'])
        ->name('admin.users.assignRole');

    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])
        ->name('admin.users.destroy');

    // Aprobación de Servicios
    Route::get('/admin/approvals', [AdminDashboardController::class, 'approvals'])
        ->name('admin.approvals.index');
    
    Route::post('/admin/approvals/{approval}/approve', [AdminDashboardController::class, 'approveService'])
        ->name('admin.approvals.approve');
    
    Route::post('/admin/approvals/{approval}/reject', [AdminDashboardController::class, 'rejectService'])
        ->name('admin.approvals.reject');
    
    Route::post('/admin/approvals/{approval}/confirm-payment', [AdminDashboardController::class, 'confirmPayment'])
        ->name('admin.approvals.confirmPayment');

    Route::get('/admin/settings', [AdminSettingsController::class, 'index'])
        ->name('admin.settings');

    Route::post('/admin/settings', [AdminSettingsController::class, 'update'])
        ->name('admin.settings.update');

    // Gestión de Galería Pública
    Route::get('/admin/gallery', [\App\Http\Controllers\AdminGalleryController::class, 'index'])
        ->name('admin.gallery.index');
    Route::post('/admin/gallery', [\App\Http\Controllers\AdminGalleryController::class, 'store'])
        ->name('admin.gallery.store');
    Route::delete('/admin/gallery/{photo}', [\App\Http\Controllers\AdminGalleryController::class, 'destroy'])
        ->name('admin.gallery.destroy');

    // Gestión de Galería de Usuarios Específicos (para el panel del dueño)
    Route::get('/admin/users/{id}/gallery', [\App\Http\Controllers\AdminGalleryController::class, 'getUserGallery'])
        ->name('admin.users.gallery');
    Route::post('/admin/users/{id}/gallery', [\App\Http\Controllers\AdminGalleryController::class, 'uploadUserGallery'])
        ->name('admin.users.gallery.store');
    Route::delete('/admin/users/{id}/gallery/{photo}', [\App\Http\Controllers\AdminGalleryController::class, 'destroyUserPhoto'])
        ->name('admin.users.gallery.destroy');
});

Route::get('/cuidador/dashboard', [CaregiverDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('cuidador.dashboard');

Route::get('/entrenador/dashboard', [TrainerDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('entrenador.dashboard');

Route::get('/entrenador/seguimiento', [TrainerModulesController::class, 'seguimiento'])
    ->middleware('auth')
    ->name('entrenador.seguimiento');

Route::post('/entrenador/seguimiento', [TrainerModulesController::class, 'storeSeguimiento'])
    ->middleware('auth')
    ->name('entrenador.seguimiento.store');

Route::get('/entrenador/mi-horario', [TrainerModulesController::class, 'horario'])
    ->middleware('auth')
    ->name('entrenador.horario');

Route::post('/entrenador/mi-horario/availability', [TrainerModulesController::class, 'updateAvailability'])
    ->middleware('auth')
    ->name('entrenador.availability.update');

Route::get('/entrenador/reservas', [TrainerModulesController::class, 'reservas'])
    ->middleware('auth')
    ->name('entrenador.reservas');

Route::post('/entrenador/reservas/{reserva}/estado', [TrainerModulesController::class, 'updateReservaEstado'])
    ->middleware('auth')
    ->name('entrenador.reservas.estado');

Route::post('/entrenador/reservas/{reserva}/cita-evaluacion', [TrainerModulesController::class, 'asignarCitaEvaluacion'])
    ->middleware('auth')
    ->name('entrenador.reservas.cita-evaluacion');

Route::post('/entrenador/reservas/{reserva}/diagnostico', [TrainerModulesController::class, 'registrarDiagnostico'])
    ->middleware('auth')
    ->name('entrenador.reservas.diagnostico');

Route::get('/entrenador/chat', [TrainerModulesController::class, 'chat'])
    ->middleware('auth')
    ->name('entrenador.chat');

Route::post('/entrenador/chat', [TrainerModulesController::class, 'sendMessage'])
    ->middleware('auth')
    ->name('entrenador.chat.send');

Route::get('/entrenador/notificaciones', [TrainerModulesController::class, 'notificaciones'])
    ->middleware('auth')
    ->name('entrenador.notificaciones');

Route::post('/entrenador/notificaciones/{id}/mark-read', [TrainerModulesController::class, 'markNotificationAsRead'])
    ->middleware('auth')
    ->name('entrenador.notifications.markRead');

Route::post('/entrenador/notificaciones/mark-all-read', [TrainerModulesController::class, 'markAllNotificationsAsRead'])
    ->middleware('auth')
    ->name('entrenador.notifications.markAllRead');

Route::get('/entrenador/perfil', [TrainerModulesController::class, 'perfil'])
    ->middleware('auth')
    ->name('entrenador.perfil');

Route::post('/entrenador/perfil', [TrainerModulesController::class, 'updatePerfil'])
    ->middleware('auth')
    ->name('entrenador.perfil.update');

// Rutas de pagos con MercadoPago
Route::get('/payment/success/{type}/{id}', [PaymentController::class, 'success'])
    ->name('payment.success');
Route::get('/payment/failure/{type}/{id}', [PaymentController::class, 'failure'])
    ->name('payment.failure');
Route::get('/payment/pending/{type}/{id}', [PaymentController::class, 'pending'])
    ->name('payment.pending');

// Respuestas de MercadoPago para reservas
Route::get('/payment/reservation/success/{id}', [PaymentController::class, 'reservationSuccess'])
    ->name('payment.reservation.success');
Route::get('/payment/reservation/failure/{id}', [PaymentController::class, 'reservationFailure'])
    ->name('payment.reservation.failure');
Route::get('/payment/reservation/pending/{id}', [PaymentController::class, 'reservationPending'])
    ->name('payment.reservation.pending');

// Webhook de MercadoPago
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook');

// Rutas públicas de apadrinamiento para invitados
Route::get('/apadrinar/{dog}', [\App\Http\Controllers\PublicPadrinoController::class, 'form'])
    ->name('public.padrino.form');
Route::post('/apadrinar/{dog}/procesar', [\App\Http\Controllers\PublicPadrinoController::class, 'process'])
    ->name('public.padrino.process');

Route::middleware('auth')->group(function () {
    Route::post('/payment/sponsorship/{sponsorDog}', [PaymentController::class, 'createSponsorshipPayment'])
        ->name('payment.sponsorship.create');
    Route::post('/payment/service/{serviceApproval}', [PaymentController::class, 'createServicePayment'])
        ->name('payment.service.create');
    Route::post('/payment/reservation/{reservation}', [PaymentController::class, 'createReservationPayment'])
        ->name('payment.reservation.create');
});

// --- MANTENIMIENTO Y REPARACIÓN ---

// REPARADOR DEFINITIVO PARA HOSTINGER (Sin funciones bloqueadas)
Route::get('/reparar-todo', function () {
    try {
        $resultados = [];
        $storagePublicPath = public_path('storage');

        // 1. Eliminar el symlink viejo si existe
        if (file_exists($storagePublicPath)) {
            if (is_link($storagePublicPath)) {
                unlink($storagePublicPath);
                $resultados[] = "Symlink eliminado ✅";
            } else {
                $resultados[] = "La carpeta 'storage' ya es física ✅";
            }
        }

        // 2. Crear la carpeta física 'public/storage' y sus subcarpetas
        $directorios = [
            $storagePublicPath,
            $storagePublicPath . '/plan-padrino',
            $storagePublicPath . '/mascotas',
            $storagePublicPath . '/galeria',
            $storagePublicPath . '/gallery/public',
        ];

        foreach ($directorios as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0775, true);
                $resultados[] = "Carpeta creada: " . basename($dir);
            }
        }

        // 3. Limpiar caché
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $resultados[] = "Caché de Laravel limpia ✅";

        // 4. FORZAR COLUMNAS DE PERFIL (Para evitar error de documento, ciudad, etc)
        if (Schema::hasTable('duenos')) {
            // Ampliar columnas existentes por si son muy cortas
            try { DB::statement('ALTER TABLE duenos MODIFY COLUMN nombre VARCHAR(255)'); } catch(\Exception $e){}
            try { DB::statement('ALTER TABLE duenos MODIFY COLUMN telefono VARCHAR(60)'); } catch(\Exception $e){}
            try { DB::statement('ALTER TABLE duenos MODIFY COLUMN direccion VARCHAR(255)'); } catch(\Exception $e){}

            // Añadir columnas faltantes una por una
            $columnasParaAgregar = [
                'documento' => "ALTER TABLE duenos ADD documento VARCHAR(80) NULL AFTER telefono",
                'ciudad' => "ALTER TABLE duenos ADD ciudad VARCHAR(120) NULL AFTER direccion",
                'fecha_nacimiento' => "ALTER TABLE duenos ADD fecha_nacimiento DATE NULL AFTER ciudad"
            ];

            foreach ($columnasParaAgregar as $col => $sql) {
                if (!Schema::hasColumn('duenos', $col)) {
                    try {
                        DB::statement($sql);
                        $resultados[] = "Columna '$col' añadida con éxito ✅";
                    } catch (\Exception $e) {
                        $resultados[] = "Error al añadir '$col': " . $e->getMessage() . " ❌";
                    }
                } else {
                    $resultados[] = "Columna '$col' ya existe ✅";
                }
            }
        } else {
            $resultados[] = "La tabla 'duenos' no existe, intentando crearla... ⚠️";
            try {
                Artisan::call('migrate', ['--force' => true]);
                $resultados[] = "Migraciones ejecutadas ✅";
            } catch(\Exception $e) {
                $resultados[] = "Error al migrar: " . $e->getMessage() . " ❌";
            }
        }
        
        return [
            'status' => 'SISTEMA REPARADO ✅',
            'mensaje' => 'Por seguridad de Hostinger, si tenías fotos viejas que no se ven, por favor súbelas de nuevo desde el panel administrativo.',
            'detalles' => $resultados
        ];
    } catch (\Exception $e) {
        return "Error crítico: " . $e->getMessage();
    }
});

// INSTALADOR DE BASE DE DATOS (Migraciones)
Route::get('/instalar-db', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return "Base de datos migrada con éxito.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});