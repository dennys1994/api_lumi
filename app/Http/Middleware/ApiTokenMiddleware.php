<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization'); // Pegue o token do cabeçalho

        if (!$token || !\App\Models\User::where('api_token', $token)->exists()) {
            return response()->json(['error' => 'Token inválido ou não fornecido'], 401);
        }

        return $next($request);
    }
}

