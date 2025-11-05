<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservar Cita') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('citasuser.store') }}">
                    @csrf

                    <!-- Fecha -->
                    <div class="mb-4">
                        <x-input-label for="fecha" :value="__('Fecha')" />
                        <x-text-input id="fecha" type="date" name="fecha" required />
                        <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Horario de atención: Lunes a Sábado</p>
                    </div>

                    <!-- Hora (con input time y validación de rango) -->
                    <div class="mb-4">
                        <x-input-label for="hora" :value="__('Hora')" />
                        <x-text-input
                            id="hora"
                            type="time"
                            name="hora"
                            required
                            class="mt-1"
                            min="08:00"
                            max="19:00" />
                        <x-input-error :messages="$errors->get('hora')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Horario de atención: 8:00 a. m. – 7:00 p. m.</p>
                    </div>

                    <!-- Servicios -->
                    <!-- Servicios (sin onchange en el HTML) -->
                    <div class="mb-4">
                        <x-input-label :value="__('Servicios')" />
                        <div class="mt-2 space-y-3">
                            @foreach($servicios as $servicio)
                            <label class="flex items-start">
                                <input
                                    type="checkbox"
                                    name="servicios[]"
                                    value="{{ $servicio->id }}"
                                    class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-3">
                                    <span class="font-medium text-gray-900">{{ $servicio->nombre }}</span><br>
                                    <span class="text-sm text-gray-600">{{ $servicio->duracion }} min</span><br>
                                    <span class="text-sm font-medium text-blue-600">${{ number_format($servicio->precio, 0, ',', '.') }}</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('servicios')" class="mt-2" />
                    </div>



                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('citasuser.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            {{ __('Reservar Cita') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function actualizarTotal() {
                let total = 0;
                document.querySelectorAll('input[name="servicios[]"]:checked').forEach(checkbox => {
                    const label = checkbox.closest('label');
                    const precioSpan = label.querySelector('.text-blue-600');
                    if (precioSpan) {
                        const precioTexto = precioSpan.textContent.replace(/[^\d]/g, '');
                        const precio = parseInt(precioTexto) || 0;
                        total += precio;
                    }
                });
                document.getElementById('total').textContent = '$' + total.toLocaleString('es-CO');
            }

            // Ejecutar al cargar
            actualizarTotal();

            // Escuchar cambios
            document.querySelectorAll('input[name="servicios[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', actualizarTotal);
            });

            // Validar al enviar
            document.querySelector('form').addEventListener('submit', function(e) {
                // Validar domingo
                const fechaInput = document.getElementById('fecha');
                const fecha = new Date(fechaInput.value);
                if (fecha.getDay() === 0) {
                    alert('No se permiten citas los domingos.');
                    e.preventDefault();
                    return;
                }

                // Validar rango de hora
                const horaInput = document.getElementById('hora');
                const hora = horaInput.value;
                if (hora && (hora < '08:00' || hora > '19:00')) {
                    alert('La hora debe estar entre las 8:00 a. m. y las 7:00 p. m.');
                    e.preventDefault();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>