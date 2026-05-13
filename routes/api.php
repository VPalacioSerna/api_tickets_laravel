<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DeviceController;

Route::get('/test-error', function () {
    throw new \Exception('Error de prueba');
});

//Rutas publicas pero con limite de auth
Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

//Rutas protegidas
Route::middleware('auth:sanctum', 'throttle:api')->group(function () {
    //autenticacion
    Route::post('/logout', [AuthController::class, 'logout']);

    //tickets
    Route::get('/tickets',          [TicketController::class, 'index']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::post('/tickets',         [TicketController::class, 'store']);
    Route::put('/tickets/{ticket}', [TicketController::class, 'update']);
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);

    //devices
    Route::get('/devices',         [DeviceController::class, 'index']);
    Route::post('/devices/assign', [DeviceController::class, 'assign']);
});
