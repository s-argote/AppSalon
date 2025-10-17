<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Administrativo') }}
        </h2>
    </x-slot>

    <!-- Contenedor principal centrado con card -->
    <div class="py-6"> <!-- Padding vertical más suave -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Saludo -->
                <p class="mb-6 text-gray-700">
                    Bienvenido, {{ Auth::user()->nombre }}
                </p>

                <!-- Botón de acción -->
                <div class="mb-6 text-center">
                    <a href="{{ route('services.index') }}"
                        class="block p-4 bg-blue-50 border border-blue-200 rounded-lg shadow-sm hover:bg-blue-100 hover:border-blue-300 transition-colors duration-200">
                        <h3 class="text-lg font-semibold text-blue-800">Gestionar Servicios</h3>
                        <p class="mt-1 text-sm text-blue-600">Crear, ver, editar y eliminar servicios del sistema.</p>
                    </a>
                </div>

                <!-- Título de la sección -->
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Últimos servicios agregados</h2>

                <!-- Tabla -->
                @if ($services->isEmpty())
                <p class="text-gray-500 italic">No hay servicios registrados.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200">
                            <tr>
                                <th scope="col" class="px-4 py-2 border text-center text-xs uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th scope="col" class="px-4 py-2 border text-center text-xs uppercase tracking-wider">
                                    Precio
                                </th>
                                <th scope="col" class="px-4 py-2 border text-center text-xs uppercase tracking-wider">
                                    Duración
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($services as $service)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                                    {{ $service->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    ${{ number_format($service->precio, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $service->duracion }} min
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>