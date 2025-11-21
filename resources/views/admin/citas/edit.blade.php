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
                        <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Selecciona un usuario</option>
                            @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}"
                                {{ old('user_id', $cita->user_id) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->nombre }} {{ $usuario->apellido }} ({{ $usuario->email }})
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>

                    <!-- Fecha -->
                    <div class="mb-4">
                        <x-input-label for="fecha" :value="__('Fecha')" />
                        <x-text-input id="fecha" type="date" name="fecha"
                            :value="old('fecha', $cita->fecha->format('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                    </div>

                    <!-- Hora -->
                    <div class="mb-4">
                        <x-input-label for="hora" :value="__('Hora')" />
                        <x-text-input
                            id="hora"
                            type="time"
                            name="hora"
                            min="08:00"
                            max="19:00"
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
                                <input type="checkbox" name="servicios[]"
                                    value="{{ $servicio->id }}"
                                    {{ in_array($servicio->id, old('servicios', $cita->servicios->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
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
                        <select name="estado" id="estado" class="mt-1 block w-full" required>
                            @foreach(['pendiente','confirmada','completada','cancelada'] as $estado)
                            <option value="{{ $estado }}"
                                {{ old('estado', $cita->estado) == $estado ? 'selected' : '' }}>
                                {{ ucfirst($estado) }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('admin.citas.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded">
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
</x-app-layout>