<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversaController;

use App\Http\Middleware\ApiTokenMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::middleware(ApiTokenMiddleware::class)->get('/test', function () {
    return response()->json(['message' => 'Token válido! Acesso permitido.']);
});

Route::middleware(ApiTokenMiddleware::class)->group(function () {
    Route::post('/conversas', [ConversaController::class, 'store']);
    Route::get('/conversas', [ConversaController::class, 'index']);
    Route::get('/conversas/client/{client_id}', [ConversaController::class, 'showByClientId']);
});