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
                        <x-input-label for="user_id" :value="__('Usuario')" />
                        <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">{{ __('Selecciona un usuario') }}</option>
                            @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}"
                                {{ old('user_id', $cita->user_id) == $usuario->id ? 'selected' : '' }}>
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
                        <p class="mt-1 text-sm text-gray-500">Horario de atención: Lunes a Sábado</p>
                    </div>

                    <!-- Hora (con input time y valor preseleccionado) -->
                    <div class="mb-4">
                        <x-input-label for="hora" :value="__('Hora')" />
                        <x-text-input
                            id="hora"
                            type="time"
                            name="hora"
                            required
                            class="mt-1"
                            min="08:00"
                            max="19:00"
                            :value="old('hora', $cita->hora->format('H:i'))" />
                        <x-input-error :messages="$errors->get('hora')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Horario de atención: 8:00 a. m. – 7:00 p. m.</p>
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
                                    {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 0) }}
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

                    <!-- Botones Cancelar y Actualizar -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.citas.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            Actualizar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Asegurar que la hora esté en el rango permitido
            document.querySelector('form').addEventListener('submit', function(e) {
                const horaInput = document.getElementById('hora');
                const value = horaInput.value;
                if (value && (value < '08:00' || value > '19:00')) {
                    alert('La hora debe estar entre las 8:00 a. m. y las 7:00 p. m.');
                    e.preventDefault();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>