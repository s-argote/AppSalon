<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Citas",
 *     description="Endpoints para gestión de citas"
 * )
 */
class CitaApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/citas/usuario/{id}",
     *     summary="Obtener citas de un usuario",
     *     tags={"Citas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Citas del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado"),
     *     @OA\Response(response=403, description="Acceso denegado")
     * )
     */
    public function getPorUsuario($id)
    {
        $usuarioAutenticado = Auth::user();

        // Si no es admin, solo puede ver sus propias citas
        if (!$usuarioAutenticado->admin && $usuarioAutenticado->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        $citas = Cita::with('servicios')->where('user_id', $id)->get();
        return response()->json([
            'success' => true,
            'data' => $citas
        ]);
    }

    /**
     * @OA\Post(
     *     path="/citas",
     *     summary="Crear nueva cita",
     *     tags={"Citas"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fecha","hora","servicios"},
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-12-10"),
     *             @OA\Property(property="hora", type="string", format="time", example="10:30"),
     *             @OA\Property(property="servicios", type="array", @OA\Items(type="integer", example=1)),
     *             @OA\Property(property="user_id", type="integer", example=3, description="ID del usuario (solo para administradores)")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Cita creada", @OA\JsonContent(type="object")),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(Request $request)
    {
        $rules = [
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
        ];

        // Si es admin, permite user_id opcional
        if (Auth::user()->admin) {
            $rules['user_id'] = 'nullable|exists:users,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        // Validar disponibilidad
        $existe = Cita::where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una cita en esta fecha y hora.'
            ], 422);
        }

        $servicios = Service::whereIn('id', $request->servicios)->get();
        $total = $servicios->sum('precio');

        // Determinar user_id
        if (Auth::user()->admin && $request->filled('user_id')) {
            $user_id = $request->user_id;
        } else {
            $user_id = Auth::id();
        }

        $cita = Cita::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'user_id' => $user_id,
            'total' => $total,
            'estado' => 'pendiente'
        ]);

        $cita->servicios()->sync($request->servicios);

        return response()->json([
            'success' => true,
            'data' => $cita->load('servicios')
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/citas/{id}",
     *     summary="Actualizar cita",
     *     tags={"Citas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fecha", type="string", format="date"),
     *             @OA\Property(property="hora", type="string", format="time"),
     *             @OA\Property(property="servicios", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="user_id", type="integer", description="ID del usuario (solo para administradores)")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cita actualizada", @OA\JsonContent(type="object")),
     *     @OA\Response(response=404, description="Cita no encontrada")
     * )
     */
    public function update(Request $request, $id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada'
            ], 404);
        }

        // Verificar permisos
        if ($cita->user_id !== Auth::id() && !Auth::user()->admin) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date',
            'hora' => 'required',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        // Validar disponibilidad (excluyendo la cita actual)
        $existe = Cita::where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una cita en esta fecha y hora.'
            ], 422);
        }

        $servicios = Service::whereIn('id', $request->servicios)->get();
        $total = $servicios->sum('precio');

        $cita->update([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'total' => $total
        ]);

        $cita->servicios()->sync($request->servicios);

        return response()->json([
            'success' => true,
            'data' => $cita->load('servicios')
        ]);
    }
}
