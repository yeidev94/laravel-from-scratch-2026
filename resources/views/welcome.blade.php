<x-layout title="Home Page">
    <h1>ISW811 Welcome to Laravel 2026</h1>
    {{ $greeting }}, {{ $person }}
    {!! $person !!}
    @dump($tasks)
    @if (count($tasks))
        <p>Yes we have tasks. How Many? <?= count($tasks) ?> tasks, in fact</p>
    @endif
  
    @foreach ( $tasks as $task)
        <li>
            {{ $task }}
        </li>
    @endforeach

    @unless (count($tasks))
        <p> there are no active tasks </p>
    @endunless
    <p>second way to use forelse empty or endforelse</p>
    @forelse ($tasks as $task)
            <li>
            {{ $task }}
        </li>
    @empty
        <p> there are no active tasks </p>
    @endforelse
    
</x-layout>
