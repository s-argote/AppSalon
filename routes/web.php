<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CitaController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

//  Página principal pública
Route::get('/', function () {
    return view('welcome');
});

//  Dashboard (solo usuarios autenticados y verificados)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


//  Rutas de perfil (solo para usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//  Panel de administración (solo para usuarios con rol admin)
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    // Dashboard del administrador
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    // CRUD de usuarios
    Route::resource('/users', UserController::class);
    // CRUD de servicios (controlador resource)
    Route::resource('/services', ServiceController::class);
    // CRUD de citas (controlador resource)
    Route::resource('/citas', CitaController::class);
});

//  Incluye las rutas de autenticación (Laravel Breeze)
require __DIR__ . '/auth.php';
