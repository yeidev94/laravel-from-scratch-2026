<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SessionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController;

Route::redirect('/', '/ideas');

Route::get('/ideas', [IdeaController::class, 'index'])->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});

Route::post('/logout', [SessionsController::class, 'destroy'])->middleware('auth');
