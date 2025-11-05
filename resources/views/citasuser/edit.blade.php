<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Editar Cita</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">

                <form method="POST" action="{{ route('citasuser.update', $cita) }}">
                    @csrf
                    @method('PUT')

                    <!-- Fecha -->
                    <div class="mb-4">
                        <x-input-label for="fecha" :value="__('Fecha')" />
                        <x-text-input id="fecha" type="date" name="fecha" :value="$cita->fecha->format('Y-m-d')" :min="now()->format('Y-m-d')" required />
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
                                <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                    {{ $cita->servicios->contains($servicio->id) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2 text-gray-700">
                                    {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 0, ',', '.') }} - {{ $servicio->duracion }} min
                                </span>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('servicios')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('citasuser.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            {{ __('Actualizar Cita') }}
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