<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Gera um token de API para o usuário.
     */
    public function token(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::withTrashed()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Credenciais inválidas',
            ], 401);
        }

        if (!$user->isActive()) {
            return response()->json([
                'error' => 'Usuário inativo',
            ], 403);
        }

        // Gerar novo token
        $token = Str::random(80);
        $user->api_token = hash('sha256', $token);
        $user->save();

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Revoga o token de API do usuário autenticado.
     */
    public function revoke(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json([
            'message' => 'Token revogado com sucesso',
        ]);
    }
}
