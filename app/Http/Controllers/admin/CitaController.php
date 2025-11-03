<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with('usuario', 'servicios')->latest()->get();
        return view('admin.citas.index', compact('citas'));
    }

    public function create()
    {
        $usuarios = User::all();
        $servicios = Service::where('activo', true)->get();
        return view('admin.citas.create', compact('usuarios', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'user_id' => 'required|exists:users,id',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
        ]);

        $servicios = Service::whereIn('id', $request->servicios)->get();
        $total = $servicios->sum('precio');

        $cita = Cita::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'user_id' => $request->user_id,
            'total' => $total,
            'estado' => $request->estado,
        ]);

        $cita->servicios()->attach($request->servicios);

        return redirect()->route('admin.citas.index')->with('success', 'Cita creada correctamente.');
    }

    public function show(Cita $cita)
    {
        $cita->load('usuario', 'servicios');
        return view('admin.citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $usuarios = User::all();
        $servicios = Service::where('activo', true)->get();
        return view('admin.citas.edit', compact('cita', 'usuarios', 'servicios'));
    }

    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'user_id' => 'required|exists:users,id',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
        ]);

        $servicios = Service::whereIn('id', $request->servicios)->get();
        $total = $servicios->sum('precio');

        $cita->update([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'user_id' => $request->user_id,
            'total' => $total,
            'estado' => $request->estado,
        ]);

        $cita->servicios()->sync($request->servicios);

        return redirect()->route('admin.citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->servicios()->detach();
        $cita->delete();
        return redirect()->route('admin.citas.index')->with('success', 'Cita eliminada correctamente.');
    }
}
