<?php

namespace Muserpol\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class VerifyBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        
        if (Hash::check('kiosko-muserpol', $token)) {
            return $next($request);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Sin Autorización',
                'data' => (object)[]
            ], 401);
        }
    }
}
