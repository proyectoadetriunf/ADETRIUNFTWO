<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/protegido', function () {
        return response()->json([
            'mensaje' => '✅ Acceso autorizado. Estás autenticada con JWT.',
            'usuario' => auth()->users()
        ]);
    });
});
