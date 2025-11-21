<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Mostrar el panel principal del administrador.
     */
    public function index()
    {
        // Zona horaria correcta
        $today = Carbon::now()->timezone('America/Bogota')->toDateString();

        // Últimos 5 servicios
        $services = Service::latest()->take(5)->get();

        // Citas de hoy
        $citasHoy = Cita::whereDate('fecha', $today)->count();

        // Ingresos de hoy
        $ingresosHoy = Cita::whereDate('fecha', $today)
            ->where('estado', '!=', 'cancelada')
            ->sum('total');

        // Total de clientes
        $clientesTotal = User::count();

        // Datos últimos 7 días
        $labels7 = [];
        $citasData7 = [];
        $ingresosData7 = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i)->timezone('America/Bogota')->toDateString();

            $labels7[] = Carbon::parse($d)->format('d/m');

            // Citas por día
            $citasData7[] = Cita::whereDate('fecha', $d)
                ->where('estado', '!=', 'cancelada')
                ->count();

            // Ingresos por día
            $ingresosData7[] = (float) Cita::whereDate('fecha', $d)
                ->where('estado', '!=', 'cancelada')
                ->sum('total');
        }

        return view('admin.dashboard', compact(
            'services',
            'citasHoy',
            'ingresosHoy',
            'clientesTotal',
            'labels7',
            'citasData7',
            'ingresosData7'
        ));
    }
}
