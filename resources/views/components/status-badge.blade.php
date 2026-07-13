@props(['status' => 'pending'])

@php
    $statusValue = $status instanceof \App\IdeaStatus
        ? $status->value
        : (string) $status;

    $classes = 'inline-block rounded-full border px-2 py-1 text-xs font-medium';

    if ($statusValue === 'pending') {
        $classes .= ' bg-yellow-500/10 text-yellow-500 border-yellow-500/20';
    }

    if ($statusValue === 'in-progress' || $statusValue === 'in_progress') {
        $classes .= ' bg-blue-500/10 text-blue-500 border-blue-500/20';
    }

    if ($statusValue === 'completed') {
        $classes .= ' bg-primary/10 text-primary border-primary/20';
    }
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot->isEmpty() ? (\App\IdeaStatus::tryFrom($statusValue)?->label() ?? $statusValue) : $slot }}
</span>
