<x-layout title="Your Ideas">
    <header class="mb-6 flex flex-col gap-4 sm:mb-8 md:mb-10 md:flex-row md:items-end md:justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold tracking-tight md:text-3xl">Your Ideas</h1>
            <p class="text-sm text-muted-foreground md:text-base">
                Capture your ideas and share them with the world.
            </p>
        </div>
    </header>

    <div class="flex flex-col gap-3 sm:gap-4 md:gap-5">
        @forelse ($ideas as $idea)
            <x-card
                :title="$idea->title"
                :description="$idea->description"
                :status="$idea->status"
                :created-at="$idea->created_at"
            />
        @empty
            <x-card tag="div" class="py-10 text-center md:py-14">
                <p class="text-base font-medium text-foreground md:text-lg">No ideas yet</p>
                <p class="mt-2 text-sm text-muted-foreground md:text-base">
                    When you add ideas, they will show up here.
                </p>
            </x-card>
        @endforelse
    </div>
</x-layout>
