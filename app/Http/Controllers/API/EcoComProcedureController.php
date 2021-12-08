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
        $eco_com_beneficiaries = EcoComBeneficiary::whereIdentityCard($identity_card)->whereBirthDate($birth_date)->orderBy('created_at', 'desc')->get();
        if ($eco_com_beneficiaries) {
            $data = collect();
            foreach($eco_com_beneficiaries as $eco_com_beneficiary) {
                $eco_com = $eco_com_beneficiary->economic_complement;
                $observations = $eco_com->observations()->where('enabled', false)->pluck('shortened')->unique();
                $data->push([
                    "id" => $eco_com->id,
                    "title" => mb_strtoupper($eco_com->eco_com_procedure->semester) . ' SEMESTRE ' . Carbon::parse($eco_com->eco_com_procedure->year)->year,
                    "beneficiario" => $eco_com->eco_com_beneficiary->fullName(),
                    "ci" => $eco_com->eco_com_beneficiary->ciWithExt(),
                    "semestre" => $eco_com->eco_com_procedure->fullName(),
                    "fecha_de_recepcion" => Util::getDateFormat($eco_com->reception_date),
                    "nro_tramite" => $eco_com->code,
                    "tipo_de_prestacion" => $eco_com->eco_com_modality->shortened,
                    "tipo_de_tramite" => $eco_com->eco_com_reception_type->name,
                    "estado" => $eco_com->eco_com_state->name,
                    "observaciones_del_tramite" => $observations->count() > 0 ? $observations->values() : 'Ninguna',
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => 'Trámites',
                'data' => $data,
            ], 200);
        }else {
            return response()->json([
                'error' => true,
                'message' => 'Persona no registrada, favor revisar la información.',
                'data' => (object)[]
            ]);
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
