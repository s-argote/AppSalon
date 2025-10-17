<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SalonFlow</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <!-- Fondo azul claro -->
    <div class="min-h-screen flex items-center justify-center bg-blue-50 px-4 py-12 sm:px-6 lg:px-8">
        <!-- Formulario de login -->
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <!-- Título -->
            <div class="flex justify-center mb-6">
                <h1 class="text-2xl font-bold text-blue-600">SalonFlow</h1>
            </div>

            <!-- Formulario -->
            {{ $slot }}

        </div>
    </div>

</body>

</html>