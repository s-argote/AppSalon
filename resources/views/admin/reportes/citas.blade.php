<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de Citas por Período
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- FILTRO POR FECHAS -->
            <div class="bg-white p-6 shadow sm:rounded-lg mb-6">

                <form method="GET" action="{{ route('admin.reportes.citas') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-medium">Fecha Inicio</label>
                        <input type="date" name="inicio" value="{{ $inicio }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Fecha Fin</label>
                        <input type="date" name="fin" value="{{ $fin }}" class="w-full border rounded p-2">
                    </div>

                    <div class="flex items-end">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                            Filtrar
                        </button>
                    </div>

                    <div class="flex items-end">
                        <a href="{{ route('admin.reportes.citas') }}" class="w-full text-center py-2 rounded bg-gray-500 hover:bg-gray-600 text-white">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- EXPORTACIONES -->
            @if($citas->count() > 0)
            <div class="mb-4 flex gap-4">
                <a href="{{ route('admin.reportes.citas.pdf', ['inicio' => $inicio, 'fin' => $fin]) }}"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Exportar PDF
                </a>

                <a href="{{ route('admin.reportes.citas.csv', ['inicio' => $inicio, 'fin' => $fin]) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Exportar CSV
                </a>
            </div>
            @endif

            <!-- TABLA DE RESULTADOS -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                @if($citas->isEmpty())
                <p class="text-gray-600">No hay citas en el período seleccionado.</p>
                @else
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="p-2 border">ID</th>
                            <th class="p-2 border">Fecha</th>
                            <th class="p-2 border">Hora</th>
                            <th class="p-2 border">Cliente</th>
                            <th class="p-2 border">Servicios</th>
                            <th class="p-2 border">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $cita)
                        <tr>
                            <td class="p-2 border text-center">{{ $cita->id }}</td>
                            <td class="p-2 border text-center">{{ $cita->fecha }}</td>
                            <td class="p-2 border text-center">{{ $cita->hora }}</td>
                            <td class="p-2 border text-center">{{ $cita->usuario->nombre }} {{ $cita->usuario->apellido }}</td>
                            <td class="p-2 border text-center">
                                @foreach($cita->servicios as $s)
                                • {{ $s->nombre }}<br>
                                @endforeach
                            </td>
                            <td class="p-2 border text-center">${{ number_format($cita->total, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>