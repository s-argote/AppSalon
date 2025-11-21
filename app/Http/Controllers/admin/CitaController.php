<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'user_id' => 'required|exists:users,id',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
        ], [
            'fecha.after_or_equal' => 'La fecha debe ser igual o posterior al día de hoy.',
            'servicios.required' => 'Debes seleccionar al menos un servicio.',
        ]);

        // Validar que no sea domingo
        if (date('w', strtotime($request->fecha)) == 0) {
            return back()->withErrors(['fecha' => 'No se permiten citas los domingos.']);
        }

        // Validar horario permitido
        if ($request->hora < '08:00' || $request->hora > '19:00') {
            return back()->withErrors(['hora' => 'La hora debe estar entre 08:00 y 19:00.']);
        }

        // Calcular duración y total
        $servicios = Service::whereIn('id', $request->servicios)->get();
        $duracion = $servicios->sum('duracion');
        $total = $servicios->sum('precio');

        // Calcular hora fin
        $horaInicio = $request->hora;
        $horaFin = date('H:i', strtotime($horaInicio . " + $duracion minutes"));

        // Validar solapamiento
        $solapa = Cita::where('fecha', $request->fecha)
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->whereBetween('hora', [$horaInicio, $horaFin])
                    ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                    ->orWhere(function ($q2) use ($horaInicio, $horaFin) {
                        $q2->where('hora', '<=', $horaInicio)
                            ->where('hora_fin', '>=', $horaFin);
                    });
            })
            ->exists();

        if ($solapa) {
            return back()->withErrors(['hora' => 'Este horario ya está ocupado por otra cita.']);
        }

        // Guardar cita
        $cita = Cita::create([
            'fecha' => $request->fecha,
            'hora' => $horaInicio,
            'hora_fin' => $horaFin,
            'duracion_total' => $duracion,
            'user_id' => $request->user_id,
            'total' => $total,
            'estado' => $request->estado,
        ]);

        $cita->servicios()->sync($request->servicios);

        return redirect()->route('admin.citas.index')->with('success', 'Cita creada correctamente.');
    }

    public function show(Cita $cita)
    {
        // Cargar relaciones
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
        // Validación básica siempre
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'user_id' => 'required|exists:users,id',
            'servicios' => 'required|array|min:1',
        ]);

        // --- Detectar si FECHA, HORA o SERVICIOS CAMBIARON ---
        $cambioFecha = $request->fecha != $cita->fecha->format('Y-m-d');
        $cambioHora = $request->hora != $cita->hora->format('H:i');
        // Detectar si cambiaron los servicios (normalizados)
        $serviciosOriginales = $cita->servicios->pluck('id')->map(fn($id) => (int)$id)->sort()->values()->toArray();
        $serviciosNuevos = collect($request->servicios)->map(fn($id) => (int)$id)->sort()->values()->toArray();

        $cambioServicios = $serviciosOriginales !== $serviciosNuevos;


        $requiereValidarSolapamiento = $cambioFecha || $cambioHora || $cambioServicios;

        // Si NO cambiaron fecha/hora/servicios → NO validar solapamiento
        if ($requiereValidarSolapamiento) {

            // No domingos
            if (date('w', strtotime($request->fecha)) == 0) {
                return back()->withErrors(['fecha' => 'No se permiten citas los domingos.']);
            }

            // Horario permitido
            if ($request->hora < '08:00' || $request->hora > '19:00') {
                return back()->withErrors(['hora' => 'La hora debe estar entre las 08:00 y 19:00.']);
            }

            // Calcular duración + total
            $servicios = Service::whereIn('id', $request->servicios)->get();
            $duracion = $servicios->sum('duracion');
            $total = $servicios->sum('precio');

            // Nueva hora fin
            $horaInicio = $request->hora;
            $horaFin = date('H:i', strtotime($horaInicio . " + $duracion minutes"));

            // Validar solapamiento (EXCLUYENDO la cita actual)
            $solapa = Cita::where('fecha', $request->fecha)
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

            if ($solapa) {
                return back()->withErrors(['hora' => 'Este horario ya está reservado por otra cita.']);
            }
        } else {
            // Si no cambia duracion, se mantiene la misma
            $duracion = $cita->duracion_total;
            $total = $cita->total;
            $horaFin = $cita->hora_fin->format('H:i');
            $horaInicio = $cita->hora->format('H:i');
        }

        // --- GUARDAR CAMBIOS ---
        $cita->update([
            'fecha' => $request->fecha,
            'hora' => $horaInicio,
            'hora_fin' => $horaFin,
            'duracion_total' => $duracion,
            'user_id' => $request->user_id,
            'total' => $total,
            'estado' => $request->estado,
        ]);

        $cita->servicios()->sync($request->servicios);

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita actualizada correctamente.');
    }




    public function destroy(Cita $cita)
    {
        $cita->servicios()->detach();
        $cita->delete();
        return redirect()->route('admin.citas.index')->with('success', 'Cita eliminada correctamente.');
    }

    public function reporteCitas(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $query = Cita::with('usuario', 'servicios');

        if ($inicio && $fin) {
            $query->whereBetween('fecha', [$inicio, $fin]);
        }

        $citas = $query->orderBy('fecha')->get();

        return view('admin.reportes.citas', compact('citas', 'inicio', 'fin'));
    }

    public function reporteIngresos(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $ingresos = Service::select('services.nombre')
            ->selectRaw('COUNT(citasServicios.service_id) as cantidad')
            ->selectRaw('SUM(services.precio) as total')
            ->join('citasServicios', 'services.id', '=', 'citasServicios.service_id')
            ->join('citas', 'citas.id', '=', 'citasServicios.cita_id')
            ->when($inicio && $fin, fn($q) => $q->whereBetween('citas.fecha', [$inicio, $fin]))
            ->groupBy('services.id', 'services.nombre')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.reportes.ingresos', compact('ingresos', 'inicio', 'fin'));
    }

    public function exportarCitasPDF(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $citas = Cita::with('usuario')
            ->when($inicio && $fin, fn($q) => $q->whereBetween('fecha', [$inicio, $fin]))
            ->get();

        $pdf = Pdf::loadView('admin.reportes.pdf.citas', compact('citas', 'inicio', 'fin'));

        return $pdf->download('reporte_citas.pdf');
    }

    public function exportarIngresosExcel(Request $request)
    {
        return Excel::download(new \App\Exports\IngresosExport(
            $request->inicio,
            $request->fin
        ), 'ingresos.xlsx');
    }

    public function exportarCitasCSV(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $citas = Cita::with('usuario')
            ->when($inicio && $fin, fn($q) => $q->whereBetween('fecha', [$inicio, $fin]))
            ->get();

        $filename = storage_path('app/citas.csv');
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Fecha', 'Hora', 'Cliente', 'Total']);

        foreach ($citas as $cita) {
            fputcsv($handle, [
                $cita->id,
                $cita->fecha,
                $cita->hora,
                $cita->usuario->nombre,
                $cita->total
            ]);
        }

        fclose($handle);

        return response()->download($filename);
    }
}
