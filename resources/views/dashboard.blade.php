<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Servicios Disponibles') }}
        </h2>
    </x-slot>

    <!-- Fondo oscuro -->
    <div class="bg-blue-600 text-white py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Encabezado -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-white">Bienvenido, {{ Auth::user()->nombre }}</h1>
                <p class="mt-2 text-lg text-white">Estos son los servicios disponibles actualmente:</p>
            </div>

            <!-- Grid de tarjetas de servicios -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($services as $service)
                <div class="bg-white text-gray-800 rounded-lg shadow-md p-5 hover:shadow-lg transition-shadow duration-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->nombre }}</h3>
                    <div class="mb-3">
                        <span class="text-sm text-gray-500">Precio:</span>
                        <span class="text-2xl font-bold text-blue-600 ml-1">${{ number_format($service->precio, 0) }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="text-sm text-gray-500">Duración:</span>
                        <span class="text-sm font-medium ml-1">{{ $service->duracion }} min</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">
                        {{ Str::limit($service->descripcion, 80, '...') }}
                    </p>
                </div>
                @endforeach

            </div>

        </div>
    </div>
</x-app-layout>