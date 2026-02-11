<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->query('api_token');

        if (! $token) {
            return response()->json(['message' => 'Token não informado.'], 401);
        }

        // Buscar usuário pelo hash do token
        $hashedToken = hash('sha256', $token);
        $user = User::where('api_token', $hashedToken)->first();

        if (! $user) {
            return response()->json(['message' => 'Token inválido.'], 401);
        }

        if (! $user->active) {
            return response()->json(['message' => 'Usuário inativo. Entre em contato com um administrador.'], 403);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
