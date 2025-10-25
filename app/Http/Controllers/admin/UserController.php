<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Mostrar lista de usuarios
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Formulario para crear usuario
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'apellido' => 'required|string|max:60',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'required|string|max:10',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        ([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'confirmado' => true, // Opcional: marcar como confirmado por defecto
            'token' => Str::random(30),
        ]);

        // Procesar el campo 'admin' correctamente
        $data = $request->except('admin');
        $data['admin'] = $request->has('admin') ? 1 : 0;

        User::create($data);


        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }
    /**
     * Mostrar detalles de un usuario
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
    /**
     * Formulario para editar usuario
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'apellido' => 'required|string|max:60',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'required|string|max:10',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
        ]);

        ([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
        ]);

        // Procesar el campo 'admin' correctamente
        $data = $request->except('admin');
        $data['admin'] = $request->has('admin');

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
