@props([
'title' => 'Laracast'
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <title>
        {{ $title }}
    </title>
    <style>
        .max-w-400 {
            max-width: 400px;
            margin: auto;
        }
        .card {
            background-color: #e3e3e3;
            padding: 1rem;
            text-align:center;
        }
    </style>
</head>
<body class="bg-gray-700 p-6 mx-w-xl mx-auto">
    {{-- <nav>
        <a href="/">Home</a>
        <a href="/about">About Us</a>
        <a href="/contact">Contact</a>
    </nav> --}}
    <main>
    {{ $slot }} 
    </main>

</body>
</html>