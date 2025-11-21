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

        /** VALIDAR DOMINGO */
        if (date('w', strtotime($request->fecha)) == 0) {
            return back()->withErrors(['fecha' => 'No se permiten citas los domingos.']);
        }

        /** VALIDAR FECHA PASADA */
        if (strtotime($request->fecha) < strtotime(date('Y-m-d'))) {
            return back()->withErrors(['fecha' => 'No puedes seleccionar una fecha pasada.']);
        }

        /** VALIDAR HORARIO */
        if ($request->hora < '08:00' || $request->hora > '19:00') {
            return back()->withErrors(['hora' => 'La hora debe estar entre 08:00 y 19:00.']);
        }

        /** CALCULAR DURACIÓN TOTAL */
        $servicios = Service::whereIn('id', $request->servicios)->get();
        $duracion = $servicios->sum('duracion');
        $total = $servicios->sum('precio');

        /** CALCULAR HORA FIN */
        $horaInicio = $request->hora;
        $horaFin = date('H:i', strtotime($horaInicio . " + $duracion minutes"));

        /** VALIDAR SOLAPAMIENTO DE CITAS */
        $existeSolapamiento = Cita::where('fecha', $request->fecha)
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->whereBetween('hora', [$horaInicio, $horaFin]) // inicio dentro de otra cita
                    ->orWhereBetween('hora_fin', [$horaInicio, $horaFin]) // fin dentro de otra cita
                    ->orWhere(function ($q2) use ($horaInicio, $horaFin) {
                        $q2->where('hora', '<=', $horaInicio)
                            ->where('hora_fin', '>=', $horaFin); // cita abarca completamente
                    });
            })
            ->exists();



        if ($existeSolapamiento) {
            return back()->withErrors([
                'hora' => 'En este horario ya se encuentra otra cita. Elige otra hora.'
            ]);
        }

        /** CREAR CITA */
        $cita = Cita::create([
            'fecha' => $request->fecha,
            'hora' => $horaInicio,
            'hora_fin' => $horaFin,
            'duracion_total' => $duracion,
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

        /** VALIDACIONES: mismas que en store() */

        if (date('w', strtotime($request->fecha)) == 0) {
            return back()->withErrors(['fecha' => 'No se permiten citas los domingos.']);
        }

        if ($request->hora < '08:00' || $request->hora > '19:00') {
            return back()->withErrors(['hora' => 'La hora debe estar entre 08:00 y 19:00.']);
        }

        $servicios = Service::whereIn('id', $request->servicios)->get();
        $duracion = $servicios->sum('duracion');
        $total = $servicios->sum('precio');

        $horaInicio = $request->hora;
        $horaFin = date('H:i', strtotime($horaInicio . " + $duracion minutes"));

        $existe = Cita::where('fecha', $request->fecha)
            ->where('id', '!=', $cita->id)
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->whereBetween('hora', [$horaInicio, $horaFin])
                    ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                    ->orWhere(function ($q2) use ($horaInicio, $horaFin) {
                        $q2->where('hora', '<=', $horaInicio)
                            ->where('hora_fin', '>=', $horaFin);
                    });
            })
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'hora' => 'En este horario se encuentra otra cita.'
            ]);
        }

        /** ACTUALIZAR */
        $cita->update([
            'fecha' => $request->fecha,
            'hora' => $horaInicio,
            'hora_fin' => $horaFin,
            'duracion_total' => $duracion,
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
