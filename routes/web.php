<?php

use App\Models\Idea;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\Auth\SessionsController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Gate;


Route::get('/', function() {
    return 'Welcome Placeholder for home page.';
});

Route::middleware('auth')->group(function(){

    Route::get('/ideas', [IdeaController::class, 'index']);

    Route::get('/ideas/create', [IdeaController::class, 'create']);

    Route::post('/ideas', [IdeaController::class, 'store']);

    Route::get('/ideas/{idea}', [IdeaController::class, 'show']);

    Route::get('/ideas/{idea}/edit', [IdeaController::class, 'edit']);

    Route::patch('/ideas/{idea}', [IdeaController::class, 'update']);

    Route::delete('/ideas/{idea}',[IdeaController::class, 'destroy']);

});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});



Route::delete('/logout', [SessionsController::class, 'destroy']);

// Route::get('/admin', function(){
//     Gate::authorize('view-admin');
//     return 'private admin area';
// });