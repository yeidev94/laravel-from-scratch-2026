<?php

use Illuminate\Support\Facades\Route;

Route::view('/contact', 'contact');
Route::view('/about', 'about');

Route::get('/', function () {
    return view('welcome', [
        'tasks' => [
            'task 1',
            'task 2',
            'task 3',
        ],
        'greeting' => 'Hello World',
        'person' => 'Yeison'
    ]);
});
