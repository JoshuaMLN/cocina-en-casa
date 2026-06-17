<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;

Route::get('/', [HomeController::class, 'index']);

// Rutas de Administración
Route::get('/admin', [AdminController::class, 'index']);
/* ==========================
   LOGIN
   ========================== */
   // --- RUTAS DE AUTENTICACIÓN (PÚBLICAS) ---
   Route::get('admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
   Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
   Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

   // --- RUTAS DEL PANEL (PROTEGIDAS) ---
   // El middleware 'auth:admin' bloquea a cualquiera que no sea administrador calificado
   Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
      
      // Tu vista principal del panel
      Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
      
      // Más rutas del panel aquí...
   });

/* ==========================
   GENERAL
   ========================== */
Route::post('/admin/logo', [AdminController::class, 'updateLogo']);
Route::post('/admin/whatsapp', [AdminController::class, 'updateWhatsapp']);

/* ==========================
   PLATOS
   ========================== */
Route::post('/admin/platos',[AdminController::class, 'storePlato']);
Route::put('/admin/platos/{plato}',[AdminController::class, 'updatePlato']);
Route::delete('/admin/platos/{plato}',[AdminController::class, 'destroyPlato']);
Route::patch('/admin/platos/{plato}/toggle',[AdminController::class, 'togglePlato']);
Route::post('/admin/platos/reordenar',[AdminController::class, 'reordenarPlatos']);
