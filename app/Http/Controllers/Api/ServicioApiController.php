<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Servicios",
 *     description="Endpoints para gestión de servicios"
 * )
 */
class ServicioApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/servicios",
     *     summary="Obtener todos los servicios activos",
     *     tags={"Servicios"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de servicios activos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $servicios = Service::where('activo', true)->get();
        return response()->json([
            'success' => true,
            'data' => $servicios
        ]);
    }

    /**
     * @OA\Get(
     *     path="/servicios/{id}",
     *     summary="Obtener un servicio por ID",
     *     tags={"Servicios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servicio encontrado",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Servicio no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Servicio no encontrado")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $servicio = Service::find($id);
        if (!$servicio) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $servicio
        ]);
    }

    /**
     * @OA\Post(
     *     path="/servicios",
     *     summary="Crear un nuevo servicio",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","precio","duracion"},
     *             @OA\Property(property="nombre", type="string", example="Tinte Capilar"),
     *             @OA\Property(property="precio", type="number", example=70000),
     *             @OA\Property(property="duracion", type="integer", example=120),
     *             @OA\Property(property="descripcion", type="string", example="Aplicación de color completo"),
     *             @OA\Property(property="activo", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Servicio creado exitosamente",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Errores de validación"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:10',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $servicio = Service::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $servicio
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/servicios/{id}",
     *     summary="Actualizar un servicio",
     *     tags={"Servicios"},
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
     *             @OA\Property(property="nombre", type="string", example="Corte Dama Premium"),
     *             @OA\Property(property="precio", type="number", example=60000),
     *             @OA\Property(property="duracion", type="integer", example=70),
     *             @OA\Property(property="descripcion", type="string", example="Corte personalizado con lavado y secado"),
     *             @OA\Property(property="activo", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servicio actualizado",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Servicio no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $servicio = Service::find($id);
        if (!$servicio) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:60',
            'precio' => 'sometimes|required|numeric|min:0',
            'duracion' => 'sometimes|required|integer|min:10',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $servicio->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $servicio
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/servicios/{id}",
     *     summary="Eliminar un servicio",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servicio eliminado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Servicio eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Servicio no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $servicio = Service::find($id);
        if (!$servicio) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ], 404);
        }

        $servicio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Servicio eliminado correctamente'
        ]);
    }
}
