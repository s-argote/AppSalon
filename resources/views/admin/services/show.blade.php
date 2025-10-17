<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Servicio') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Servicio</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nombre:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $service->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Precio:</p>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($service->precio, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Duración:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $service->duracion }} min</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Descripción:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $service->descripcion }}</p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-6 flex justify-start space-x-3">
                    <a href="{{ route('services.edit', $service) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-700">
                        Editar
                    </a>
                    <a href="{{ route('services.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-white bg-gray-500 hover:bg-gray-700">
                        Volver
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>