<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicioApiController;
use App\Http\Controllers\Api\CitaApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TokenController;

// Servicios públicos
Route::get('servicios', [ServicioApiController::class, 'index']);
Route::get('servicios/{id}', [ServicioApiController::class, 'show']);

// Autenticación API
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthApiController::class, 'register']);
    Route::post('login', [AuthApiController::class, 'login']);
});

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthApiController::class, 'logout']);
    Route::post('auth/logout-all', [AuthApiController::class, 'logoutAll']);
    Route::get('auth/profile', [AuthApiController::class, 'profile']);
    Route::put('auth/profile', [AuthApiController::class, 'updateProfile']);
    Route::get('citas/usuario/{id}', [CitaApiController::class, 'getPorUsuario']);
    Route::post('citas', [CitaApiController::class, 'store']);
    Route::put('citas/{id}', [CitaApiController::class, 'update']);
    Route::get('users', [UserApiController::class, 'index']);
    Route::get('users/{id}', [UserApiController::class, 'show']);
    Route::post('users', [UserApiController::class, 'store']);
    Route::put('users/{id}', [UserApiController::class, 'update']);
    Route::delete('users/{id}', [UserApiController::class, 'destroy']);
    Route::post('servicios', [ServicioApiController::class, 'store']);
    Route::put('servicios/{id}', [ServicioApiController::class, 'update']);
    Route::delete('servicios/{id}', [ServicioApiController::class, 'destroy']);
    Route::get('tokens', [TokenController::class, 'index']);
    Route::delete('tokens/{id}', [TokenController::class, 'destroy']);
});
