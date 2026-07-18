<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
class RefreshTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            // Token not found or invalid, initiate a refresh
            try {
                $newToken = JWTAuth::parseToken()->refresh();

                // Attach the new token to the response headers
                $response = $next($request);
                $response->headers->set('Authorization', 'Bearer ' . $newToken);

                return $response;
            } catch (\Exception $e) {
                // Token refresh failed, return an unauthorized response
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        return $next($request);
    }
}
