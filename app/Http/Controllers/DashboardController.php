<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\Cita;

class DashboardController extends Controller
{
    /**
     * Mostrar el panel del usuario (cliente o admin).
     */
    public function index()
    {

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
        // Si es admin, redirigir al panel de administración
        if ($user->admin) {
            return redirect()->route('admin.dashboard');
        }

        $citas = Cita::with('servicios')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $services = Service::where('activo', true)->get();

        return view('dashboard', compact('citas', 'services'));
    }
}
