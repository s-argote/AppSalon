<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Usuario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Usuario</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nombre:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->nombre }} {{ $user->apellido }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Teléfono:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->telefono ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rol:</p>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($user->admin)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-black">
                                Administrador
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-black">
                                Usuario
                            </span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-6 flex justify-start space-x-3">
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-white bg-gray-500 hover:bg-gray-700">
                        Volver
                    </a>
                    <a href="{{ route('users.edit', $user) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-700">
                        Editar
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>