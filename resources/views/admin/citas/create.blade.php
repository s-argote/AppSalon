<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Crear Cita</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">

                <form method="POST" action="{{ route('citas.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="user_id" :value="__('Usuario')" />
                        <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">{{ __('Selecciona un usuario') }}</option>
                            @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->nombre }} {{ $usuario->apellido }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="fecha" :value="__('Fecha')" />
                        <x-text-input id="fecha" type="date" name="fecha" required />
                        <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="hora" :value="__('Hora')" />
                        <x-text-input id="hora" type="time" name="hora" required />
                        <x-input-error :messages="$errors->get('hora')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label :value="__('Servicios')" />
                        <div class="mt-2 space-y-2">
                            @foreach($servicios as $servicio)
                            <label class="flex items-center">
                                <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700">
                                    {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('servicios')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="estado" :value="__('Estado')" />
                        <select name="estado" id="estado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="pendiente">{{ __('Pendiente') }}</option>
                            <option value="confirmada">{{ __('Confirmada') }}</option>
                            <option value="completada">{{ __('Completada') }}</option>
                            <option value="cancelada">{{ __('Cancelada') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('citas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">Cancelar</a>
                        <x-primary-button>Crear Cita</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>