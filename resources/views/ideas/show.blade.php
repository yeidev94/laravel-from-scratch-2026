<x-layout >
    <div class="card bg-neutral-300 p-6 mt-6 text-blue-950">
            {{$idea->description}}
        <div class="mt-6 ">
            <a 
                href="/ideas/{{$idea->id}}/edit"
                class="btn"
            >
                Save
            </a>
        </div>
    </div>
</x-layout>