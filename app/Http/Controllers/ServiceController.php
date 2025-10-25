<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Mostrar lista de servicios
     */
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Formulario para crear servicio
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Guardar nuevo servicio
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'duracion' => 'nullable|integer|min:10',
        ], [
            'precio.min' => 'El precio no puede ser negativo.',
            'duracion.min' => 'La duración mínima es de 10 minutos.',
        ]);

        // Procesar el campo 'activo' correctamente
        $data = $request->except('activo');
        $data['activo'] = $request->has('activo') ? 1 : 0;

        Service::create($data);

        return redirect()->route('services.index')->with('success', 'Servicio creado correctamente.');
    }
    /**
     * Mostrar detalles de un servicio
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }
    /**
     * Formulario para editar servicio
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Actualizar servicio
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'duracion' => 'nullable|integer|min:10',
        ], [
            'precio.min' => 'El precio no puede ser negativo.',
            'duracion.min' => 'La duración mínima es de 10 minutos.',
        ]);

        // Procesar el campo 'activo' correctamente
        $data = $request->except('activo');
        $data['activo'] = $request->has('activo') ? 1 : 0;

        $service->update($data);

        return redirect()->route('services.index')->with('success', 'Servicio actualizado correctamente.');
    }

    /**
     * Eliminar servicio
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Servicio eliminado correctamente.');
    }
}
