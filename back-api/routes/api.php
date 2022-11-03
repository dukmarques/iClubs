<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/admin', [AdminController::class, 'create']);
Route::post('/auth', [AuthController::class, 'login']);

Route::get('/unauthenticated', function () {
    return response()->json(['error' => 'Usuário não logado'], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::post('/users', [UserController::class, 'create']);
    Route::get('/user/{id}', [UserController::class, 'getUser']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);

    Route::get('/clubs', [ClubController::class, 'getAllClubs']);
    Route::post('/clubs', [ClubController::class, 'create']);
    Route::get('/club/{id}', [ClubController::class, 'getClub']);
    Route::put('/club/{id}', [ClubController::class, 'update']);
    Route::delete('/club/{id}', [ClubController::class, 'delete']);
    Route::get('/club/available/{id}', [ClubController::class, 'getClubsAvailableToSign']);

    Route::post('/signatures/{userId}/{clubId}', [SignatureController::class, 'create']);

    Route::put('/invoice/{id}', [InvoiceController::class, 'editPayment']);
    Route::get('/invoices/{signatureId}', [InvoiceController::class, 'getBySignature']);
});
