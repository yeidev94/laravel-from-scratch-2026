@props(['title' => 'Idea'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground">
    <x-layout.nav />

    <main class="max-w-3xl mx-auto mt-6 p-6 lg:p-8">
        {{ $slot }}
    </main>

    {{-- <div x-data="{ show: true }">
        <button @click="show = !show" class="btn">Toggle</button>
        <p x-show="show">you can see me</p>
    </div> --}}
    @session('success')
        <div
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            class="bg-blue-500 text-white p-4 rounded-md"
            >

            {{ $value }}
        </div>
    @endsession

</body>
</html>
