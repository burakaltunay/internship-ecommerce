<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Bearer token kontrolü
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token bulunamadı. Lütfen giriş yapın.'
            ], 401);
        }

        // Token geçerliliğini kontrol et
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz token. Lütfen tekrar giriş yapın.'
            ], 401);
        }

        return $next($request);
    }
}
