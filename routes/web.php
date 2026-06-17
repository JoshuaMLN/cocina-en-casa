<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index']);

// Rutas de Administración
Route::get('/admin', [AdminController::class, 'index']);
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