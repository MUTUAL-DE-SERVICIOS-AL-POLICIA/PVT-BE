<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\EconomicComplement\EcoComState;
use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;

class EcoComStateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'error' => true,
            'message' => 'Estados de trámites de Complemento Económico',
            'data' => [
                'eco_com_states' => EcoComState::with('eco_com_state_type')->get()
            ]
        ]);
    }
}
