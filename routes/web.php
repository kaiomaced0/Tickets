<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme');

    Route::resource('tickets', TicketController::class)->except(['create', 'edit']);
});

require __DIR__.'/auth.php';
