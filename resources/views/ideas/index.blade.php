<x-layout >
    @if ($ideas->count())    
        <div class="mt-6 text-white">
            <h2>Your Ideas</h2>
            @foreach ( $ideas as $idea)
                <li>
                    <a href="/ideas/{{ $idea->id }}">
                        {{ $idea->description }}
                    </a>
                </li>
            @endforeach
        </div>
    @else
    <p class="text-white">No Ideas Yet. <a class="underline" href="/ideas/create">Create a New One</a></p>
    @endif
</x-layout>