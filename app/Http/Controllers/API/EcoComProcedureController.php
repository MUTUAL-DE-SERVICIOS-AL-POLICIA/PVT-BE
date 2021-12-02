<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\Affiliate;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EcoComStateType;
use Muserpol\Http\Resources\EconomicComplementResource;
use Muserpol\Http\Resources\EcoComProcedureResource;
use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Helpers\Util;
use Carbon\Carbon;

class EcoComProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $identity_card = mb_strtoupper($request->identity_card);
        $birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');

        if (Util::isDoblePerceptionEcoCom($identity_card)) {
            return response()->json([
                'error' => true,
                'message' => 'Usted percibe el Beneficio como Titular y Viuda(o).',
                'data' => (object)[]
            ], 403);
        } else {
            $eco_com_beneficiary = EcoComBeneficiary::whereIdentityCard($identity_card)->whereBirthDate($birth_date)->first();
            if ($eco_com_beneficiary) {
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate;
                $data = $affiliate->economic_complements()->has('eco_com_beneficiary')->orderBy('reception_date', 'desc');
                $current_procedures = EcoComProcedure::current_procedures()->pluck('id');
                $state_types = EcoComStateType::whereIn('name', ['Enviado', 'Creado'])->pluck('id');
                $data = $data->where(function($q) use ($current_procedures, $state_types) {
                    $q->whereIn('eco_com_procedure_id', $current_procedures)->orWhereHas('eco_com_state', function ($query) use ($state_types) {
                        return $query->whereIn('eco_com_state_type_id', $state_types);
                    });
                });
                return response()->json([
                    'error' => false,
                    'message' => 'Trámite vigente',
                    'data' => [
                        'data' => EconomicComplementResource::collection($data)->resource,
                    ]
                ], 200);
            }else {
                return response()->json([
                    'error' => true,
                    'message' => 'Usted no tiene trámites registrados, para mayor información pasar por oficinas de la MUSERPOL.',
                    'data' => (object)[]
                ], 403);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return \Illuminate\Http\Response
     */
    public function show(EcoComProcedure $ecoComProcedure)
    {
        return new EcoComProcedureResource($ecoComProcedure);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return \Illuminate\Http\Response
     */
    public function edit(EcoComProcedure $ecoComProcedure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EcoComProcedure $ecoComProcedure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcoComProcedure $ecoComProcedure)
    {
        //
    }
}
