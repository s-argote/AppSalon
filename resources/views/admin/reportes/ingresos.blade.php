<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de Ingresos por Servicio
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- FILTRO -->
            <div class="bg-white p-6 shadow sm:rounded-lg mb-6">

                <form method="GET" action="{{ route('admin.reportes.ingresos') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
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
                        <a href="{{ route('admin.reportes.ingresos') }}" class="w-full text-center py-2 rounded bg-gray-500 hover:bg-gray-600 text-white">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- EXPORTAR A EXCEL -->
            @if($ingresos->count() > 0)
            <div class="mb-4">
                <a href="{{ route('admin.reportes.ingresos.excel', ['inicio' => $inicio, 'fin' => $fin]) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Exportar a Excel
                </a>
            </div>
            @endif

            <!-- TABLA -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                @if($ingresos->isEmpty())
                <p class="text-gray-600">No se registran ingresos en ese período.</p>
                @else
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="p-2 border">Servicio</th>
                            <th class="p-2 border">Cantidad Vendida</th>
                            <th class="p-2 border">Total Generado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingresos as $item)
                        <tr>
                            <td class="p-2 border text-center">{{ $item->nombre }}</td>
                            <td class="p-2 border text-center">{{ $item->cantidad }}</td>
                            <td class="p-2 border text-center">${{ number_format($item->total, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>