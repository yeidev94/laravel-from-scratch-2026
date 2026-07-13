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
</body>
</html>
