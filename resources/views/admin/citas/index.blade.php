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

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-center">
                        <thead class="bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs text-black uppercase tracking-wider">
                                    Id
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs text-black uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs text-black uppercase tracking-wider">
                                    Hora
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs text-black uppercase tracking-wider">
                                    Hora Fin
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs text-black uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs text-black uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs text-black uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs text-black uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($citas as $cita)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $cita->id }}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $cita->fecha->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $cita->hora->format('g:i') . ($cita->hora->hour < 12 ? ' a. m.' : ' p. m.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $cita->hora_fin->format('g:i') . ($cita->hora_fin->hour < 12 ? ' a. m.' : ' p. m.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $cita->usuario->nombre }} {{ $cita->usuario->apellido }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($cita->total, 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($cita->estado === 'pendiente')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-300 text-black">
                                        Pendiente
                                    </span>
                                    @elseif($cita->estado === 'confirmada')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-300 text-black">
                                        Confirmada
                                    </span>
                                    @elseif($cita->estado === 'completada')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-300 text-black">
                                        Completada
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-300 text-black">
                                        Cancelada
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">

                                    <!-- Botón Ver siempre visible -->
                                    <a href="{{ route('admin.citas.show', $cita) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-500 hover:bg-green-700 mr-1">
                                        Ver
                                    </a>

                                    @if($cita->estado === 'pendiente')
                                    <!-- Botón Editar -->
                                    <a href="{{ route('admin.citas.edit', $cita) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-500 hover:bg-blue-700 mr-1">
                                        Editar
                                    </a>

                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('admin.citas.destroy', $cita) }}" method="POST" class="inline"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar esta cita?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-500 hover:bg-red-700">
                                            Eliminar
                                        </button>
                                    </form>

                                    @else
                                    <!-- Mensaje cuando la cita ya no es editable -->
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded text-xs font-medium bg-gray-300 text-black">
                                        Esta cita no es editable
                                    </span>
                                    @endif

                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Botones al final -->
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.citas.create') }}"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                        Nueva Cita
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>