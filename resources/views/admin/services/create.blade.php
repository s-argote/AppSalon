<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Servicio') }}
        </h2>
    </x-slot>

    <!-- Contenedor principal centrado con card -->
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Formulario -->
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf

                    <!-- Campo Nombre -->
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" id="nombre"
                            value="{{ old('nombre') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Precio -->
                    <div class="mb-4">
                        <label for="precio" class="block text-sm font-medium text-gray-700">Precio</label>
                        <input type="number" step="0.01" name="precio" id="precio"
                            value="{{ old('precio') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        @error('precio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Descripción -->
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="descripcion" id="descripcion"
                            rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Duración -->
                    <div class="mb-6">
                        <label for="duracion" class="block text-sm font-medium text-gray-700">Duración (minutos)</label>
                        <input type="number" name="duracion" id="duracion"
                            value="{{ old('duracion') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        @error('duracion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Activo -->
                    <div class="mt-6">
                        <x-input-label for="activo" :value="__('Activo')" />
                        <div class="mt-1 flex items-center">
                            <!-- Solo el toggle está dentro del label -->
                            <label for="activo" class="relative inline-flex items-center cursor-pointer">
                                <input id="activo" name="activo" type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-red-500 rounded-full peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:ring-offset-2 peer-focus:ring-offset-white peer-checked:bg-green-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>

                            <!-- El texto está fuera del label -->
                            <span class="ml-3 text-sm font-medium text-gray-900">¿Este servicio estará activo?</span>
                        </div>
                        <x-input-error :messages="$errors->get('activo')" class="mt-2" />
                    </div>

                    <!-- Botones Cancelar y Guardar -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.services.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            Guardar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>