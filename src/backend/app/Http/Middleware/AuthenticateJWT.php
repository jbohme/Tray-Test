<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
            $request->merge(['user_id' => JWTAuth::parseToken()->getPayload()->toArray()['sub']]);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine(), $e->getTrace());
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
