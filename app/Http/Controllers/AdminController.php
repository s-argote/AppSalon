<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class AdminController extends Controller
{
    /**
     * Mostrar el panel principal del administrador.
     */
    public function index()
    {
        // Obtener todos los servicios (para mostrar un resumen)
        $services = Service::latest()->take(5)->get();

        return view('admin.dashboard', compact('services'));
    }
}
