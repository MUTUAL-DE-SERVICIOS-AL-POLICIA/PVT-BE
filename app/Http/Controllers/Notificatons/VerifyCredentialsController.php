<?php

namespace Muserpol\Http\Controllers\Notificatons;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;

class VerifyCredentialsController extends Controller
{
    public function verify(){
        return response()->json([
            'saludo' => 'hola'
        ]);
    }
}
