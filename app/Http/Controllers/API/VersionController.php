<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;

class VersionController extends Controller
{
    public function versiones() {
        return response()->json([
            'error' => false,
            'message' => 'Versiones de aplicaciones en tiendas',
            'data' => [
                'playstore' => '2.1.1',
                'appstore' => '2.1.1',
                'appgallery' => '2.1.1'
            ]
            ]);
    }
}
