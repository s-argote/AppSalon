<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * @OA\Get(
     * path="/tokens",
     * summary="Listar tokens activos",
     * description="Retorna todos los tokens activos del usuario autenticado",
     * operationId="listTokens",
     * tags={"Gestión de Tokens"},
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Lista de tokens obtenida exitosamente",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Tokens obtenidos exitosamente"),
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="auth_token"),
     * @OA\Property(property="last_used_at", type="string", format="date-time", nullable=true),
     * @OA\Property(property="created_at", type="string", format="date-time")
     * )
     * )
     * )
     * )
     * )
     */
    public function index(Request $request)
    {
        $tokens = $request->user()->tokens;
        $tokensData = $tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
            ];
        });
        return response()->json([
            'success' => true,
            'message' => 'Tokens obtenidos exitosamente',
            'data' => $tokensData,
            'total' => $tokensData->count()
        ], 200);
    }
    /**
     * @OA\Delete(
     * path="/tokens/{id}",
     * summary="Revocar token específico",
     * description="Revoca un token específico del usuario autenticado",
     * operationId="revokeToken",
     * tags={"Gestión de Tokens"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID del token a revocar",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Token revocado exitosamente"
     * ),
     * @OA\Response(
     * response=404,
     * description="Token no encontrado"
     * )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $token = $request->user()->tokens()->where('id', $id)->first();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token no encontrado'
            ], 404);
        }
        $token->delete();
        return response()->json([
            'success' => true,
            'message' => 'Token revocado exitosamente'
        ], 200);
    }
}
