<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Detalles de la Cita</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fecha</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $cita->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Hora</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $cita->hora->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Usuario</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $cita->usuario->nombre }} {{ $cita->usuario->apellido }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estado</p>
                        <p class="mt-1 text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full
                                @if($cita->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($cita->estado === 'confirmada') bg-blue-100 text-blue-800
                                @elseif($cita->estado === 'completada') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($cita->total, 2) }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900">Servicios</h3>
                    <ul class="mt-2 space-y-1">
                        @foreach($cita->servicios as $servicio)
                        <li class="text-sm text-gray-700">• {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6 flex space-x-3">
                    <a href="{{ route('citas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">Volver</a>
                    <a href="{{ route('citas.edit', $cita) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Editar</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>