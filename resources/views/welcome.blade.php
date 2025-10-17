<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SalonFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Encabezado -->
    <header class="bg-blue-600 text-white py-4 shadow">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold">SalonFlow</h1>
            <nav>
                @auth
                @if (Auth::user()->admin)
                <a href="{{ route('admin.dashboard') }}" class="px-3 hover:underline">Panel Administrativo</a>
                @else
                <a href="{{ route('dashboard') }}" class="px-3 hover:underline">Servicios Disponibles</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 hover:underline">Salir</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="px-3 hover:underline">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="px-3 hover:underline">Registrarse</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Sección principal -->
    <section class="text-center py-16 bg-gradient-to-b from-blue-100 to-white">
        <h2 class="text-4xl font-bold text-blue-700 mb-4">Bienvenido a SalonFlow</h2>
        <p class="text-lg text-gray-700 mb-6">
            Reserva tus servicios de belleza favoritos desde cualquier lugar.
        </p>
        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700">
            Ver Servicios
        </a>
    </section>

    <!-- Sección de ejemplo de servicios -->
    <section class="max-w-6xl mx-auto py-12 px-6">
        <h3 class="text-2xl font-semibold mb-6 text-center text-gray-800">Nuestros Servicios Destacados</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition">
                <h4 class="font-semibold text-center text-blue-600 mb-2">Corte de Cabello Dama</h4>
                <p class="text-gray-700 text-center text-sm">Corte personalizado que resalta tu estilo.</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition">
                <h4 class="font-semibold text-center text-blue-600 mb-2">Manicure y Pedicure</h4>
                <p class="text-gray-700 text-center text-sm">Cuida tus manos y pies con nuestros expertos.</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition">
                <h4 class="font-semibold text-center text-blue-600 mb-2">Tinte Capilar</h4>
                <p class="text-gray-700 text-center text-sm">Color vibrante y duradero con productos profesionales.</p>
            </div>
        </div>
    </section>

</body>

</html>