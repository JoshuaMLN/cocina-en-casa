<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SolicitudServicioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

/* ==========================
   SOLICITUDES PÚBLICAS
   ========================== */
Route::post('/solicitar-servicio', [
    SolicitudServicioController::class,
    'store',
])->middleware('throttle:5,10')->name('solicitudes.store');

/* ==========================
   AUTENTICACIÓN ADMIN
   ========================== */
Route::get('admin/login', [
    AdminAuthController::class,
    'showLogin',
])->name('admin.login');

Route::post('admin/login', [
    AdminAuthController::class,
    'login',
])->name('admin.login.submit');

/* ==========================
   PANEL ADMIN PROTEGIDO
   ========================== */
Route::middleware(['auth:admin', 'auth.session'])
    ->prefix('admin')
    ->group(function () {
    Route::get('/', [
        AdminController::class,
        'index',
    ])->name('admin.dashboard');

    Route::post('/logout', [
        AdminAuthController::class,
        'logout',
    ])->name('admin.logout');

    Route::post('/logo', [
        AdminController::class,
        'updateLogo',
    ])->name('admin.logo.update');

    Route::post('/whatsapp', [
        AdminController::class,
        'updateWhatsapp',
    ])->name('admin.whatsapp.update');

    Route::post('/nosotros', [
        AdminController::class,
        'updateNosotros',
    ])->name('admin.nosotros.update');

    Route::post('/footer', [
        AdminController::class,
        'updateFooter',
    ])->name('admin.footer.update');

    Route::post('/configuracion/desbloquear', [
        AdminAccountController::class,
        'unlock',
    ])->middleware('throttle:admin-settings-unlock')
        ->name('admin.settings.unlock');

    Route::patch('/configuracion/correo', [
        AdminAccountController::class,
        'updateEmail',
    ])->middleware('throttle:admin-settings-update')
        ->name('admin.settings.email');

    Route::patch('/configuracion/contrasena', [
        AdminAccountController::class,
        'updatePassword',
    ])->middleware('throttle:admin-settings-update')
        ->name('admin.settings.password');

    Route::post('/platos', [
        AdminController::class,
        'storePlato',
    ])->name('admin.platos.store');

    Route::put('/platos/{plato}', [
        AdminController::class,
        'updatePlato',
    ])->name('admin.platos.update');

    Route::delete('/platos/{plato}', [
        AdminController::class,
        'destroyPlato',
    ])->name('admin.platos.destroy');

    Route::patch('/platos/{plato}/toggle', [
        AdminController::class,
        'togglePlato',
    ])->name('admin.platos.toggle');

    Route::post('/platos/reordenar', [
        AdminController::class,
        'reordenarPlatos',
    ])->name('admin.platos.reordenar');

    Route::patch('/solicitudes/{solicitud}/leer', [
        SolicitudServicioController::class,
        'marcarLeida',
    ])->name('admin.solicitudes.leer');

    Route::patch('/solicitudes/{solicitud}/estado', [
        SolicitudServicioController::class,
        'actualizarEstado',
    ])->name('admin.solicitudes.estado');

    Route::delete('/solicitudes/{solicitud}', [
        SolicitudServicioController::class,
        'destroy',
    ])->name('admin.solicitudes.destroy');
    });
