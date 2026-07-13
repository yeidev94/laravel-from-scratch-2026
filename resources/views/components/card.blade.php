@props([
    'title' => null,
    'description' => null,
    'status' => null,
    'createdAt' => null,
    'tag' => 'article',
])

@php
    $hasStructuredContent = filled($title)
        || filled($description)
        || filled($status)
        || isset($heading)
        || isset($body)
        || isset($badge);
@endphp

<{{ $tag }} {{ $attributes->class(['card']) }}>
    @if ($hasStructuredContent)
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
            <div class="min-w-0 flex-1 space-y-2">
                @isset($heading)
                    {{ $heading }}
                @elseif (filled($title))
                    <h2 class="card-title">{{ $title }}</h2>
                @endif

                @isset($body)
                    {{ $body }}
                @elseif (filled($description))
                    <p class="card-text line-clamp-3 md:line-clamp-none">
                        {{ $description }}
                    </p>
                @endif
            </div>

            @isset($badge)
                <div class="self-start">
                    {{ $badge }}
                </div>
            @elseif (filled($status))
                <x-status-badge :status="$status" class="self-start">
                    {{ $status instanceof \App\IdeaStatus ? $status->label() : $status }}
                </x-status-badge>
            @endisset
        </div>
    @elseif (! $slot->isEmpty())
        {{ $slot }}
    @endif

    @isset($footer)
        <footer class="mt-4 flex items-center justify-between border-t border-border pt-3 text-xs text-muted-foreground md:mt-5 md:pt-4 md:text-sm">
            {{ $footer }}
        </footer>
    @elseif ($createdAt)
        <footer class="mt-4 flex items-center justify-between border-t border-border pt-3 text-xs text-muted-foreground md:mt-5 md:pt-4 md:text-sm">
            <time datetime="{{ $createdAt->toDateString() }}">
                {{ $createdAt->diffForHumans() }}
            </time>
        </footer>
    @endisset
</{{ $tag }}>
