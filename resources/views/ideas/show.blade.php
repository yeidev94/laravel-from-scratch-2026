<x-layout >
    <div class="mt-6 text-white">
        {{$idea->description}}
    </div>
    <div class="mt-6 text-white flex items-center gap-x-6">
        <a 
         href="/ideas/{{$idea->id}}/edit"
         class="rounded-md bg-green-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
         >
            Edit
        </a>
    </div>
</x-layout>