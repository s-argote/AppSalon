<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Servicios') }}
        </h2>
    </x-slot>

    <!-- Contenedor principal centrado con card -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Sección de Consulta de Servicios -->
                <div class="mb-4 border-b border-gray-200 pb-3">
                    <h3 class="text-lg font-semibold text-gray-800">Consulta de Servicios</h3>
                    <p class="mt-1 text-sm text-gray-700">
                        Total de Registros: <span class="font-bold text-blue-800">{{ $services->count() }}</span>
                    </p>
                </div>

                <!-- Tabla de servicios -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-center">
                        <thead class="bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-black-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-black-500 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-black-500 uppercase tracking-wider">
                                    Precio
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-black-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($services as $service)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $service->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $service->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($service->precio, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <!-- Botón Ver -->
                                    <a href="{{ route('services.show', $service) }}"
                                        class="px-2.5 py-1.5 bg-green-500 text-white rounded hover:bg-green-700 mr-1">
                                        Ver
                                    </a>
                                    <!-- Botón Editar -->
                                    <a href="{{ route('services.edit', $service) }}"
                                        class="px-2.5 py-1.5 bg-blue-500 text-white rounded hover:bg-blue-700 mr-1">
                                        Editar
                                    </a>
                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('services.destroy', $service) }}" method="POST"
                                        style="display:inline-block"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2.5 py-1.5 bg-red-500 text-white rounded hover:bg-red-700 mr-1">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Botones al final -->
                <div class="mt-6 flex flex-wrap gap-3">
                    <!-- Botón 1: Volver al Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                        Volver al Panel Administrativo
                    </a>
                    <!-- Botón 2: Nuevo Servicio -->
                    <a href="{{ route('services.create') }}"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                        Nuevo Servicio
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>