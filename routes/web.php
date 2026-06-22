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


Route::get('/', function () {
   $ideas = session()->get('ideas', []);
    return view('ideas', [
        'ideas' => $ideas
    ]);
});

Route::post('/ideas', function () {
    $idea = request('idea');
    session() -> push('ideas', $idea);
    return redirect('/');
});

//temporary
Route::get('/delete-ideas', function() {
    session()->forget('ideas');

    return redirect('/');
});