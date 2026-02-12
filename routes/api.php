<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileApiController;
use Illuminate\Support\Facades\Route;

// Rotas de autenticação (não precisam de token)
Route::post('/auth/token', [AuthController::class, 'token']);

// Rotas públicas de recuperação de senha
Route::post('/auth/forgot-password', [ProfileApiController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [ProfileApiController::class, 'resetPassword']);

Route::middleware('auth.token')->group(function (): void {
    // Revogar token
    Route::post('/auth/revoke', [AuthController::class, 'revoke']);

    Route::get('/dashboard', [DashboardController::class, 'api']);

    // Rotas de Perfil (usuário autenticado)
    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::patch('/profile', [ProfileApiController::class, 'update']);
    Route::put('/profile/password', [ProfileApiController::class, 'updatePassword']);
    Route::post('/profile/deactivate', [ProfileApiController::class, 'deactivate']);
    Route::patch('/profile/theme', [ProfileApiController::class, 'updateTheme']);

    // Rotas de Tickets (qualquer usuário autenticado)
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::patch('/tickets/{ticket}', [TicketController::class, 'update']);
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus']);
    Route::post('/tickets/{ticket}/toggle-active', [TicketController::class, 'destroy']);

    // Rotas de Users (apenas admin)
    Route::middleware('api.admin')->group(function (): void {
        Route::get('/users', [UserApiController::class, 'index']);
        Route::post('/users', [UserApiController::class, 'store']);
        Route::get('/users/{user}', [UserApiController::class, 'show']);
        Route::patch('/users/{user}', [UserApiController::class, 'update']);
        Route::post('/users/{user}/toggle-active', [UserApiController::class, 'toggleActive']);
    });
});
