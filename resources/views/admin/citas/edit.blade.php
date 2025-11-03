<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cita') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">

                <form method="POST" action="{{ route('admin.citas.update', $cita) }}">
                    @csrf
                    @method('PUT')

                    <!-- Usuario -->
                    <div class="mb-4">
                        <x-input-label for="usuarioId" :value="__('Usuario')" />
                        <select name="usuarioId" id="usuarioId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">{{ __('Selecciona un usuario') }}</option>
                            @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}"
                                {{ old('usuarioId', $cita->usuarioId) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->nombre }} {{ $usuario->apellido }} ({{ $usuario->email }})
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('usuarioId')" class="mt-2" />
                    </div>

                    <!-- Fecha -->
                    <div class="mb-4">
                        <x-input-label for="fecha" :value="__('Fecha')" />
                        <x-text-input
                            id="fecha"
                            type="date"
                            name="fecha"
                            :value="old('fecha', $cita->fecha->format('Y-m-d'))"
                            required />
                        <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                    </div>

                    <!-- Hora -->
                    <div class="mb-4">
                        <x-input-label for="hora" :value="__('Hora')" />
                        <x-text-input
                            id="hora"
                            type="time"
                            name="hora"
                            :value="old('hora', $cita->hora->format('H:i'))"
                            required />
                        <x-input-error :messages="$errors->get('hora')" class="mt-2" />
                    </div>

                    <!-- Servicios -->
                    <div class="mb-4">
                        <x-input-label :value="__('Servicios')" />
                        <div class="mt-2 space-y-2">
                            @foreach($servicios as $servicio)
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="servicios[]"
                                    value="{{ $servicio->id }}"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    {{ in_array($servicio->id, old('servicios', $cita->servicios->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">
                                    {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('servicios')" class="mt-2" />
                    </div>

                    <!-- Estado -->
                    <div class="mb-4">
                        <x-input-label for="estado" :value="__('Estado')" />
                        <select
                            name="estado"
                            id="estado"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="pendiente" {{ old('estado', $cita->estado) == 'pendiente' ? 'selected' : '' }}>
                                {{ __('Pendiente') }}
                            </option>
                            <option value="confirmada" {{ old('estado', $cita->estado) == 'confirmada' ? 'selected' : '' }}>
                                {{ __('Confirmada') }}
                            </option>
                            <option value="completada" {{ old('estado', $cita->estado) == 'completada' ? 'selected' : '' }}>
                                {{ __('Completada') }}
                            </option>
                            <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>
                                {{ __('Cancelada') }}
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.citas.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            {{ __('Cancelar') }}
                        </a>
                        <x-primary-button>
                            {{ __('Actualizar Cita') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>