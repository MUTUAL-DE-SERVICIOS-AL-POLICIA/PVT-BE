<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\EconomicComplement\EconomicComplement;
use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;

class EconomicComplementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json([
            'error' => false,
            'message' => 'Trámites de Complemento Económico',
            'data' => $request->affiliate->economic_complements()->latest()->paginate($request->per_page ?? 10, ['id', 'code', 'reception_date', 'total_amount_semester', 'difference', 'total', 'eco_com_state_id'], 'page', $request->page ?? 1)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\Models\EconomicComplement\EconomicComplement  $economicComplement
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, EconomicComplement $economicComplement)
    {
        if ($economicComplement->affiliate_id == $request->affiliate->id) {
            $response = $economicComplement->only('id', 'code', 'reception_date', 'total_amount_semester', 'difference', 'total');
            $response['eco_com_state'] = $economicComplement->eco_com_state->name;
            $response['eco_com_state_type'] = $economicComplement->eco_com_state->eco_com_state_type->name;
            $response['wf_current_state'] = $economicComplement->wf_state->name;
            $response['city'] = $economicComplement->city->name;
            $response['category'] = $economicComplement->category->name;
            return response()->json([
                'error' => false,
                'message' => 'Trámite de Complemento Económico ' . $economicComplement->code,
                'data' => $response
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Este trámite no le pertenece',
                'data' => []
            ], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
}
