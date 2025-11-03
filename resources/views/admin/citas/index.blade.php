<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Citas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <div class="mb-4 border-b border-gray-200 pb-3">
                    <h3 class="text-lg font-semibold text-gray-800">Consulta de Citas</h3>
                    <p class="mt-1 text-sm text-gray-700">
                        Total de Registros: <span class="font-bold text-blue-800">{{ $citas->count() }}</span>
                    </p>
                </div>

                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white p-6 rounded-lg shadow">

                            <table class="min-w-full divide-y divide-gray-200 text-center">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium text-black uppercase">Fecha</th>
                                        <th class="px-6 py-3 text-xs font-medium text-black uppercase">Hora</th>
                                        <th class="px-6 py-3 text-xs font-medium text-black uppercase">Usuario</th>
                                        <th class="px-6 py-3 text-xs font-medium text-black uppercase">Total</th>
                                        <th class="px-6 py-3 text-xs font-medium text-black uppercase">Estado</th>
                                        <th class="px-6 py-3 text-xs font-medium text-black uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($citas as $cita)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cita->fecha->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cita->hora->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cita->usuario->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($cita->total, 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs font-semibold rounded-full
                                        @if($cita->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                        @elseif($cita->estado === 'confirmada') bg-blue-100 text-blue-800
                                        @elseif($cita->estado === 'completada') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($cita->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('citas.show', $cita) }}" class="text-green-600 hover:text-green-900 mr-2">Ver</a>
                                            <a href="{{ route('citas.edit', $cita) }}" class="text-blue-600 hover:text-blue-900 mr-2">Editar</a>
                                            <form action="{{ route('citas.destroy', $cita) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cita?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
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
                            <a href="{{ route('citas.create') }}"
                                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                                Nueva Cita
                            </a>
                        </div>
                    </div>
                </div>
</x-app-layout>