<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CitaController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\CitaUserController;
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
    Route::get('/reservar', [CitaUserController::class, 'create'])->name('citasuser.create');
    Route::post('/reservar', [CitaUserController::class, 'store'])->name('citasuser.store');
    Route::get('/mis-citas', [CitaUserController::class, 'index'])->name('citasuser.index');
    Route::get('/mis-citas/{cita}/editar', [CitaUserController::class, 'edit'])->name('citasuser.edit');
    Route::put('/mis-citas/{cita}', [CitaUserController::class, 'update'])->name('citasuser.update');
    Route::delete('/mis-citas/{cita}', [CitaUserController::class, 'destroy'])->name('citasuser.destroy');
});

// Panel de administración (solo rol admin)
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::resource('/users', UserController::class);
        Route::resource('/services', ServiceController::class);
        Route::resource('/citas', CitaController::class);

        // Reportes
        Route::get('/reportes/citas', [CitaController::class, 'reporteCitas'])
            ->name('reportes.citas');

        Route::get('/reportes/ingresos', [CitaController::class, 'reporteIngresos'])
            ->name('reportes.ingresos');

        // Exportaciones
        Route::get('/reportes/citas/pdf', [CitaController::class, 'exportarCitasPDF'])
            ->name('reportes.citas.pdf');

        Route::get('/reportes/ingresos/excel', [CitaController::class, 'exportarIngresosExcel'])
            ->name('reportes.ingresos.excel');

        Route::get('/reportes/citas/csv', [CitaController::class, 'exportarCitasCSV'])
            ->name('reportes.citas.csv');
    });

//  Incluye las rutas de autenticación (Laravel Breeze)
require __DIR__ . '/auth.php';
