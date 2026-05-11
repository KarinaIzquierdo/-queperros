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
use App\Http\Controllers\OwnerPetController;
use App\Http\Controllers\OwnerServiceController;
use App\Http\Controllers\OwnerReservaController;
use App\Http\Controllers\OwnerModulesController;
use App\Http\Controllers\CaregiverDashboardController;
use App\Http\Controllers\TrainerDashboardController;
use App\Http\Controllers\TrainerModulesController;
use App\Http\Controllers\AdminSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

Route::get('/dashboard/reservas', [OwnerModulesController::class, 'reservas'])
    ->middleware('auth')
    ->name('owner.reservas');

Route::get('/dashboard/seguimiento', [OwnerModulesController::class, 'seguimiento'])
    ->middleware('auth')
    ->name('owner.seguimiento');

Route::get('/dashboard/pagos', [OwnerModulesController::class, 'pagos'])
    ->middleware('auth')
    ->name('owner.pagos');

Route::get('/dashboard/plan-padrino', [OwnerModulesController::class, 'planPadrino'])
    ->middleware('auth')
    ->name('owner.planpadrino');

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

Route::get('/dashboard/notificaciones', [OwnerModulesController::class, 'notificaciones'])
    ->middleware('auth')
    ->name('owner.notificaciones');

Route::get('/dashboard/galeria', [OwnerModulesController::class, 'galeria'])
    ->middleware('auth')
    ->name('owner.galeria');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/users', [AdminUserController::class, 'index'])
        ->name('admin.users');

    Route::get('/admin/services', [AdminServiceController::class, 'index'])
        ->name('admin.services');

    Route::get('/admin/pets', [AdminPetController::class, 'index'])
        ->name('admin.pets');

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

    Route::post('/admin/users/assign-role', [AdminUserController::class, 'assignRole'])
        ->name('admin.users.assignRole');

    Route::get('/admin/settings', [AdminSettingsController::class, 'index'])
        ->name('admin.settings');

    Route::post('/admin/settings', [AdminSettingsController::class, 'update'])
        ->name('admin.settings.update');
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

Route::get('/entrenador/mi-horario', [TrainerModulesController::class, 'horario'])
    ->middleware('auth')
    ->name('entrenador.horario');

Route::post('/entrenador/mi-horario/availability', [TrainerModulesController::class, 'updateAvailability'])
    ->middleware('auth')
    ->name('entrenador.availability.update');

Route::get('/entrenador/reservas', [TrainerModulesController::class, 'reservas'])
    ->middleware('auth')
    ->name('entrenador.reservas');

Route::get('/entrenador/chat', [TrainerModulesController::class, 'chat'])
    ->middleware('auth')
    ->name('entrenador.chat');

Route::get('/entrenador/notificaciones', [TrainerModulesController::class, 'notificaciones'])
    ->middleware('auth')
    ->name('entrenador.notificaciones');

Route::get('/entrenador/perfil', [TrainerModulesController::class, 'perfil'])
    ->middleware('auth')
    ->name('entrenador.perfil');
