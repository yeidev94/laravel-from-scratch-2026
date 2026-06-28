<x-layout >
    @if ($ideas->count())    
        <div class="mt-6 text-white">
            <h2>Your Ideas</h2>
            <ul class="mt-6 grid grid-cols-2 gap-x-6 gap-y-4">
            @foreach ( $ideas as $idea)
                <x-idea-card href="/ideas/{{ $idea->id }}">
                    {{ $idea->description}}
                </x-idea-card>
            @endforeach
            </ul>
        </div>
    @else
    <p>No Ideas yet.</p>
    @endif
    <p class="text-white">No Ideas Yet. <a class="underline" href="/ideas/create">Create a New One</a></p>
</x-layout>