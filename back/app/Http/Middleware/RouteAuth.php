<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class RouteAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if(!$token){
            return response()->json(['error' => 'Invalid token'], 422);
        }

        $accessToken = PersonalAccessToken::where('token', $token)->first();

        if(!$accessToken){
            return response()->json(['error' => 'Invalid token'], 422);
        }

        return $next($request);
    }
}
