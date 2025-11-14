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
            'fecha' => 'required|date',
            'hora' => 'required',
            'user_id' => 'required|exists:users,id',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
        ]);

        if (Cita::where('fecha', $request->fecha)->where('hora', $request->hora)->exists()) {
            return back()->withErrors(['hora' => 'Ya existe una cita en esta fecha y hora.']);
        }

        $servicios = Service::whereIn('id', $request->servicios)->get();
        $total = $servicios->sum('precio');

        $cita = Cita::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'user_id' => $request->user_id,
            'total' => $total,
            'estado' => $request->estado,
        ]);

        $cita->servicios()->sync($request->servicios);

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
