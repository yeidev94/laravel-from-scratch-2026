@props(['title', 'description'])

<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center -my-6 lg:-my-8">
    <div class="w-full max-w-md space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-2xl font-bold tracking-tight">{{ $title }}</h1>
            <p class="text-muted-foreground">{{ $description }}</p>
        </div>

        {{ $slot }}
    </div>
</div>
