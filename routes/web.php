<?php

use App\Models\Idea;
use Illuminate\Support\Facades\Route;

Route::view('/contact', 'contact');
Route::view('/about', 'about');

// index
Route::get('/ideas', function () {
    $ideas = Idea::all();

    return view('ideas.index', [
        'ideas' => $ideas,
    ]);
});

// show
Route::get('/ideas/{idea}', function (Idea $idea) {
    // $idea = Idea::find($id);
   // $idea = Idea::findOrFail($id);

    // if(is_null($idea)){
    //     abort(404);
    // }

    return view('ideas.show', [
        'idea'=>$idea
    ]);
});

// edit show the page
Route::get('/ideas/{idea}/edit', function (Idea $idea) {
    return view('ideas.edit', [
        'idea'=>$idea
    ]);
});

// update this one makes the patch
Route::patch('/ideas/{idea}', function (Idea $idea) {
    $idea->update([
        'description' => request('description'),
    ]);
    return redirect("/ideas/{$idea->id}");
});

Route::post('/ideas', function () {
    Idea::create([
        'description' => request('idea'),
        'state' => 'pending',
    ]);

    return redirect('/ideas');
});

Route::get('/delete-ideas', function() {

    Idea::truncate();

    return redirect('/ideas');
});

// destroy
Route::delete('/ideas/{idea}', function (Idea $idea) {
    $idea->delete();
    return redirect('/ideas');
});
