<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\EconomicComplement\EconomicComplement;
use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Http\Resources\EconomicComplementResource;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EcoComStateType;

class EconomicComplementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->affiliate->economic_complements()->orderBy('reception_date', 'desc');
        $current_procedures = EcoComProcedure::current_procedures(true)->pluck('id');
        if (filter_var($request->query('current'), FILTER_VALIDATE_BOOLEAN, false)) {
            $state_types = EcoComStateType::whereIn('name', ['Enviado', 'Creado'])->pluck('id');
            $data = $data->where(function($q) use ($current_procedures, $state_types) {
                $q->whereIn('eco_com_procedure_id', $current_procedures)->orWhereHas('eco_com_state', function ($query) use ($state_types) {
                    return $query->whereIn('eco_com_state_type_id', $state_types);
                });
            });
        } else {
            $state_types = EcoComStateType::whereIn('name', ['Pagado', 'No Efectivizado'])->pluck('id');
            $data = $data->where(function($q) use ($current_procedures, $state_types) {
                $q->whereNotIn('eco_com_procedure_id', $current_procedures)->whereHas('eco_com_state', function ($query) use ($state_types) {
                    return $query->whereIn('eco_com_state_type_id', $state_types);
                });
            });
        }
        return response()->json([
            'error' => false,
            'message' => 'Complemento Económico',
            'data' => EconomicComplementResource::collection($data->paginate($request->per_page ?? 3, ['*'], 'page', $request->page ?? 1))->resource,
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
            return response()->json([
                'error' => false,
                'message' => 'Complemento Económico ' . $economicComplement->code,
                'data' => new EconomicComplementResource($economicComplement),
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Este trámite no le pertenece',
                'data' => null,
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
