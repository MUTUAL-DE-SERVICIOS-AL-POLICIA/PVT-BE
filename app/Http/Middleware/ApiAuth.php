<?php

namespace Muserpol\Http\Middleware;

use Closure;
use Muserpol\Models\AffiliateDevice;
use Illuminate\Support\Facades\Hash;
use Muserpol\Models\AffiliateToken;


class ApiAuth
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
        if ($request->bearerToken()) {
            $device = AffiliateToken::whereApiToken($request->bearerToken())->first();
            if ($device) {
                // if (Hash::check($device->device_id, $device->api_token)) {
                //     $request->merge(['affiliate' => $device->affiliate]);
                //     return $next($request);
                // }
                $request->merge(['affiliate' => $device->affiliate]);
                return $next($request); 
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error de autenticación',
                'data' => []
            ], 401);
        }
        return response()->json([
            'error' => true,
            'message' => 'Dispositivo inválido',
            'data' => []
        ], 403);
    }
}
