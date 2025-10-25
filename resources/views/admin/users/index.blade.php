<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <div class="mb-4 border-b border-gray-200 pb-3">
                    <h3 class="text-lg font-semibold text-gray-800">Consulta de Usuarios</h3>
                    <p class="mt-1 text-sm text-gray-700">
                        Total de Registros: <span class="font-bold text-blue-800">{{ $users->count() }}</span>
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-center">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-black uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-black uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-black uppercase tracking-wider">
                                    Teléfono
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-black uppercase tracking-wider">
                                    Rol
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium text-black uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $user->nombre }} {{ $user->apellido }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->telefono }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($user->admin)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-300 text-black">
                                        Administrador
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-300 text-black">
                                        Usuario
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <!-- Botón Ver -->
                                    <a href="{{ route('users.show', $user) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-500 hover:bg-green-700 mr-1">
                                        Ver
                                    </a>
                                    <!-- Botón Editar -->
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-500 hover:bg-blue-700 mr-1">
                                        Editar
                                    </a>
                                    <!-- Botón Eliminar -->
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-500 hover:bg-red-700">
                                            Eliminar
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Botones al final -->
                <div class="mt-6 flex flex-wrap gap-3">
                    <!-- Botón 1: Volver al Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                        Volver al Panel Administrativo
                    </a>
                    <!-- Botón 2: Nuevo Servicio -->
                    <a href="{{ route('users.create') }}"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                        Nuevo Usuario
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>