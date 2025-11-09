<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(
 *     title="API de SalonFlow",
 *     version="1.0.0",
 *     description="API REST para gestión de usuarios, servicios y citas"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/AppSalon/public/api",
 *     description="Servidor de desarrollo"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Endpoints para registro, login, logout y gestión de perfil"
 * )
 */
class AuthApiController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Registrar nuevo usuario",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","apellido","email","password","telefono"},
     *             @OA\Property(property="nombre", type="string", example="Ana"),
     *             @OA\Property(property="apellido", type="string", example="López"),
     *             @OA\Property(property="email", type="string", format="email", example="ana@salonflow.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *             @OA\Property(property="telefono", type="string", example="3101234567")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado y autenticado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Registro exitoso"),
     *             @OA\Property(property="token", type="string", example="1|abcdefghijklmnopqrstuvwxyz"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:60',
            'apellido' => 'required|string|max:60',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'telefono' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $user = User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'admin' => false,
            'confirmado' => true,
            'token' => \Illuminate\Support\Str::random(30),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registro exitoso',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Iniciar sesión",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="cliente@salonflow.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login exitoso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Inicio de sesión exitoso"),
     *             @OA\Property(property="token", type="string", example="1|abcdefghijklmnopqrstuvwxyz"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciales inválidas")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Cerrar sesión (token actual)",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Sesión cerrada", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="success", type="boolean", example=true),
     *         @OA\Property(property="message", type="string", example="Sesión cerrada correctamente")
     *     )),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/logout-all",
     *     summary="Cerrar todas las sesiones",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Todas las sesiones cerradas", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="success", type="boolean", example=true),
     *         @OA\Property(property="message", type="string", example="Todas las sesiones han sido cerradas")
     *     )),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todas las sesiones han sido cerradas'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/auth/profile",
     *     summary="Obtener perfil del usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil del usuario",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * @OA\Put(
     *     path="/auth/profile",
     *     summary="Actualizar perfil del usuario",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Ana María"),
     *             @OA\Property(property="apellido", type="string", example="López Díaz"),
     *             @OA\Property(property="telefono", type="string", example="3159876543")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Perfil actualizado", @OA\JsonContent(type="object")),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:60',
            'apellido' => 'sometimes|required|string|max:60',
            'telefono' => 'sometimes|required|string|max:15',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $user = $request->user();
        $user->update($request->only(['nombre', 'apellido', 'telefono']));

        return response()->json($user);
    }
}
