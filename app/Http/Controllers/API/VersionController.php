<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;

class VersionController extends Controller
{
    public function get_version($bool, $store_url) {
        return response()->json([
            'error' => $bool,
            'message' => $bool ? 'Versión correcta' : 'Actualiza la nueva versión',
            'data' => [
                'url_store' => $store_url
            ]
        ]);
    }
    public function version(Request $request) {
        $store = $request->store;
        $version = $request->version; 
        switch($store) {
            case 'playstore':
                $url_store = 'https://play.google.com/store/apps/details?id=com.muserpol.pvt';
                return ($version == "4.1.1")? $this->get_version(true, $url_store) : $this->get_version(false, $url_store);
            case 'appstore':
                $url_store = 'https://apps.apple.com/app/id284815942';
                return $version == "4.1.1" ? $this->get_version(true, $url_store) : $this->get_version(false, $url_store);
            case 'appgallery':
                $url_store = 'https://appgallery.huawei.com/app/C106440831';
                return ($version == "4.1.1")? $this->get_version(true, $url_store) : $this->get_version(false, $url_store);
            default:
                return response()->json([
                    'error' => true,
                    'message' => 'Parámetros incorrectos',
                    'data' => []
                ], 404);
        }
    }
}