<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-4">
                        <x-input-label for="nombre" :value="__('Nombre')" />
                        <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre', $user->nombre)" required autofocus autocomplete="nombre" />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- Apellido -->
                    <div class="mb-4">
                        <x-input-label for="apellido" :value="__('Apellido')" />
                        <x-text-input id="apellido" class="block mt-1 w-full" type="text" name="apellido" :value="old('apellido', $user->apellido)" required autocomplete="apellido" />
                        <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-4">
                        <x-input-label for="telefono" :value="__('Teléfono')" />
                        <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono', $user->telefono)" required autocomplete="telefono" />
                        <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                    </div>

                    <!-- Rol (Admin) -->
                    <div class="mb-4">
                        <x-input-label for="admin" :value="__('Rol')" />
                        <div class="mt-1 flex items-center">
                            <input id="admin" name="admin" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" {{ old('admin', $user->admin) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Administrador</span>
                        </div>
                        <x-input-error :messages="$errors->get('admin')" class="mt-2" />
                    </div>

                    <!-- Botones Cancelar y Actualizar -->
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('users.index') }}"
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
</x-app-layout>