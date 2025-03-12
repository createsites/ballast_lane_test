<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PersonalAccessTokenController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('personal-access-tokens', [PersonalAccessTokenController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tables', TableController::class);
    Route::apiResource('bookings', BookingController::class);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return response()->json($request->user());
})/*->middleware('auth:sanctum')*/;
