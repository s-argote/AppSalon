<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaUserController extends Controller
{
    public function index()
    {
        $citas = Cita::with('servicios')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('citasuser.index', compact('citas'));
    }

    public function create()
    {
        $servicios = Service::where('activo', true)->get();
        return view('citasuser.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
        ]);

        // Validar disponibilidad
        if (Cita::where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->exists()
        ) {
            return back()->withErrors(['hora' => 'Ya existe una cita en esta fecha y hora. Por favor elige otro horario.']);
        }

        $servicios = Service::whereIn('id', $request->servicios)->get();
        $total = $servicios->sum('precio');

        $cita = Cita::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'user_id' => Auth::id(),
            'total' => $total,
            'estado' => 'pendiente',
        ]);

        $cita->servicios()->sync($request->servicios);

        return redirect()->route('citasuser.index')->with('success', '¡Cita reservada con éxito!');
    }

    public function edit(Cita $cita)
    {
        if ($cita->user_id !== Auth::id() || $cita->estado !== 'pendiente') {
            abort(403, 'No puedes editar esta cita.');
        }
        $servicios = Service::where('activo', true)->get();
        return view('citasuser.edit', compact('cita', 'servicios'));
    }

    public function update(Request $request, Cita $cita)
    {
        if ($cita->user_id !== Auth::id() || $cita->estado !== 'pendiente') {
            abort(403, 'No puedes editar esta cita.');
        }

        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
        ]);

        // Validar disponibilidad (excepto la misma cita)
        $existe = Cita::where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->where('id', '!=', $cita->id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['hora' => 'Ya existe una cita en esta fecha y hora.']);
        }

        $servicios = Service::whereIn('id', $request->servicios)->get();
        $total = $servicios->sum('precio');

        $cita->update([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'total' => $total,
        ]);

        $cita->servicios()->sync($request->servicios);

        return redirect()->route('citasuser.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        if ($cita->user_id !== Auth::id()) {
            abort(403, 'No puedes cancelar esta cita.');
        }

        $cita->update(['estado' => 'cancelada']);

        return redirect()->route('citasuser.index')->with('success', 'Cita cancelada correctamente.');
    }
}
