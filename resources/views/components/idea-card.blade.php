<a {{ $attributes->merge(['class'=>'card text-neutral-content w-96']) }} >
    <div class="card-body">
        <p class="text-blue-950">{{ $slot }}</p>
    </div>
</a>