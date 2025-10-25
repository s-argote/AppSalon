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
                                <th scope="col" class="px-4 py-2 border text-center text-xs uppercase tracking-wider">
                                    Estado
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
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($service->activo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-300 text-black">
                                        Activo
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-300 text-black">
                                        Inactivo
                                    </span>
                                    @endif
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