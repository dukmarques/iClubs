<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/admin', [AdminController::class, 'create']);
Route::post('/auth', [AuthController::class, 'login']);

Route::get('/unauthenticated', function () {
    return response()->json(['error' => 'Usuário não logado'], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/logout', [AuthController::class, 'logout']);
});
