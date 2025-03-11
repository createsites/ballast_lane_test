<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tables', TableController::class); // CRUD для столиков
    Route::apiResource('bookings', BookingController::class); // CRUD для бронирований
});

// Регистрация и авторизация
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
