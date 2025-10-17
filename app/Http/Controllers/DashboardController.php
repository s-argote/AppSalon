<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Service;

class DashboardController extends Controller
{
    /**
     * Mostrar el panel del usuario (cliente o admin).
     */
    public function index()
    {
        // Si es admin, redirigir al panel de administración
        if (Auth::user()->admin) {
            return redirect()->route('admin.dashboard');
        }

        // Si es usuario normal, mostrar los servicios disponibles
        $services = Service::where('activo', true)->get();

        return view('dashboard', compact('services'));
    }
}
