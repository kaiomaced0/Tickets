<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): JsonResponse|mixed
    {
        $token = $request->bearerToken() ?? $request->query('api_token');

        if (! $token) {
            return response()->json(['message' => 'Token não informado.'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (! $user) {
            return response()->json(['message' => 'Token inválido.'], 401);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
