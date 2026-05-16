<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $plain = $data['password'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($plain),
        ]);

        // El enunciado pide enviar las credenciales por email tras registro.
        // Mala práctica en producción real, pero es lo que pide el enunciado.
        try {
            Mail::to($user->email)->send(new WelcomeMail($user, $plain));
        } catch (\Throwable $e) {
            // Si el mail falla, no rompemos el registro
            logger()->warning('Error enviando bienvenida', ['error' => $e->getMessage()]);
        }

        $token = $user->createToken('api')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'Usuario registrado correctamente. Revisa tu email.', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->error('Credenciales incorrectas', null, 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'Login correcto');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success([
            'user' => $request->user(),
            'total_points' => $request->user()->totalPoints(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Sesión cerrada');
    }
}
