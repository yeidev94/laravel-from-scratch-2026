<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Http\Requests\IdeaRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; 
use App\Notifications\IdeaPublished;
use App\Jobs\UpdateIdeaStatistics;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ideas = Auth::user()->ideas;

        return view('ideas.index', [
            'ideas' => $ideas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Idea::class);
        return view('ideas.create');
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdeaRequest $request)
    {

        $idea = Auth::user()->ideas()->create([
            'description' => $request->description,
            'state' => 'pending',
            'user_id' => Auth::id(),
        ]);

        // notify user
        Auth::user()->notify(new IdeaPublished($idea));

        return redirect('/ideas');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        Gate::authorize('update', $idea);

        return view('ideas.show', [
            'idea'=>$idea
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        return view('ideas.edit', [
            'idea'=>$idea
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdeaRequest $request, Idea $idea)
    {
        $idea->update([
            'description' => $request->description,
        ]);

        return redirect("/ideas/{$idea->id}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        Gate::authorize('delete', $idea);
        $idea->delete();

        return redirect('/ideas');
    }
}
