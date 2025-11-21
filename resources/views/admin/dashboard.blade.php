<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resumen Estadistico') }}
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                    <!-- Citas hoy -->
                    <div class="bg-blue-100 p-6 rounded-lg shadow">
                        <p class="text-sm text-blue-700 font-semibold">Citas del día</p>
                        <h2 class="text-3xl font-bold text-blue-900 mt-2">{{ $citasHoy }}</h2>
                    </div>

                    <!-- Ingresos hoy -->
                    <div class="bg-green-100 p-6 rounded-lg shadow">
                        <p class="text-sm text-green-700 font-semibold">Ingresos de hoy</p>
                        <h2 class="text-3xl font-bold text-green-900 mt-2">${{ number_format($ingresosHoy, 0) }}</h2>
                    </div>

                    <!-- Total clientes -->
                    <div class="bg-purple-100 p-6 rounded-lg shadow">
                        <p class="text-sm text-purple-700 font-semibold">Total clientes</p>
                        <h2 class="text-3xl font-bold text-purple-900 mt-2">{{ $clientesTotal }}</h2>
                    </div>
                </div>

                <!-- GRÁFICAS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

                    <!-- Gráfica de Citas -->
                    <div class="bg-white shadow p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-4 text-gray-700">Citas últimos 7 días</h3>
                        <canvas id="chartCitas"></canvas>
                    </div>

                    <!-- Gráfica de Ingresos -->
                    <div class="bg-white shadow p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-4 text-gray-700">Ingresos últimos 7 días</h3>
                        <canvas id="chartIngresos"></canvas>
                    </div>

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
                                <th scope="col" class="px-4 py-2 border text-center text-xs uppercase tracking-wider">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($services as $service)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $service->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    ${{ number_format($service->precio, 0) }}
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