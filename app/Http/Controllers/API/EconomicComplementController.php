<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Http\Resources\EconomicComplementResource;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EcoComStateType;
use Muserpol\Models\EconomicComplement\EcoComModality;
use Muserpol\Helpers\Util;
use Muserpol\Helpers\ID;
use Carbon\Carbon;

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
        $current_procedures = EcoComProcedure::current_procedures()->pluck('id');
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
            ], 200);
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
        $eco_com_procedure_id = $request->eco_com_procedure_id;
        $affiliate = $request->affiliate;
        $last_eco_com = $request->affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) { $q->orderBy('year')->orderBy('normal_start_date'); })->latest()->first();
        $now = Carbon::now()->toDateString();
        /**
         ** Create Economic Complement 
         */
        $economic_complement = new EconomicComplement();
        $economic_complement->user_id = 1;
        $economic_complement->affiliate_id = $affiliate->id;
        $economic_complement->eco_com_modality_id = EcoComModality::where('procedure_modality_id','=',$last_eco_com->eco_com_modality->procedure_modality_id)->where('name', 'like', '%normal%')->first()->id;
        $economic_complement->eco_com_state_id = ID::ecoComState()->in_process;
        $economic_complement->eco_com_procedure_id = $eco_com_procedure_id;
        $economic_complement->workflow_id = EcoComProcedure::whereDate('normal_end_date', '>=', $now)->first()->id? ID::workflow()->eco_com_normal : ID::workflow()->eco_com_additional;
        $economic_complement->wf_current_state_id = 1;
        $economic_complement->city_id = ID::cityId()->LP;
        $economic_complement->degree_id = $affiliate->degree->id;
        $economic_complement->category_id = $affiliate->category->id;
        $economic_complement->code = Util::getLastCodeEconomicComplement($eco_com_procedure_id);
        $economic_complement->reception_date = now();
        $economic_complement->inbox_state = false;
        $economic_complement->eco_com_reception_type_id = ID::ecoCom()->habitual;

        if ($affiliate->pension_entity_id == ID::pensionEntity()->senasir) {
            $economic_complement->sub_total_rent = Util::parseMoney($last_eco_com->sub_total_rent);
            $economic_complement->reimbursement = Util::parseMoney($last_eco_com->reimbursement);
            $economic_complement->dignity_pension = Util::parseMoney($last_eco_com->dignity_pension);
            $economic_complement->aps_disability = Util::parseMoney($last_eco_com->aps_disability);
            $economic_complement->aps_total_fsa = null;
            $economic_complement->aps_total_cc = null;
            $economic_complement->aps_total_fs = null;
            $economic_complement->total_rent =
            $economic_complement->sub_total_rent -
            $economic_complement->reimbursement -
            $economic_complement->dignity_pension +
            $economic_complement->aps_disability;
        } else {
            $economic_complement->aps_total_fsa = Util::parseMoney($last_eco_com->aps_total_fsa);
            $economic_complement->aps_total_cc = Util::parseMoney($last_eco_com->aps_total_cc);
            $economic_complement->aps_total_fs = Util::parseMoney($last_eco_com->aps_total_fs);
            $economic_complement->aps_disability = Util::parseMoney($last_eco_com->aps_disability);
            $economic_complement->sub_total_rent = null;
            $economic_complement->reimbursement = null;
            $economic_complement->dignity_pension = null;
            $economic_complement->total_rent =
            $economic_complement->aps_total_fsa +
            $economic_complement->aps_total_cc +
            $economic_complement->aps_total_fs +
            $economic_complement->aps_disability;
        }
        $economic_complement->save();
        /**
         ** Save eco com beneficiary
         */
        $eco_com_beneficiary = new EcoComBeneficiary();
        $eco_com_beneficiary = $last_eco_com->eco_com_beneficiary;
        $eco_com_beneficiary->save();
        /**
         ** has affiliate observation
         */
        $observations = $affiliate->observations()->where('type', 'AT')->get();
        foreach ($observations as $observation) {
            $economic_complement->observations()->save($observation, [
                'user_id' => $observation->pivot->user_id,
                'date' => $observation->pivot->date,
                'message' => $observation->pivot->message,
                'enabled' => false
            ]);
        }
        /**
         ** observacion mayor de 25 en orfandad
         */
        if ($economic_complement->eco_com_modality_id == ID::ecoCom()->orphanhood && $eco_com_beneficiary->birth_date) {
            $beneficiary_years = intval(explode(' ', Util::calculateAge($eco_com_beneficiary->birth_date, null)[0]));
            if ($beneficiary_years > 25) {
                $economic_complement->observations()->save(ObservationType::find(36), [
                    'user_id' => auth()->id(),
                    'date' => now(),
                    'message' => 'Excluido - Huerfano(a) cumplio 25 años. (Observación adicionada automáticamente)',
                    'enabled' => false
                ]);
            }
        }    
        /**
         ** Update or create address
         */
        //$eco_com_beneficiary->address()->save($last_eco_com->eco_com_beneficiary->address()->first());
        
        return response()->json([
            'error' => false,
            'message' => 'Complemento Económico creado',
            'data' => null,
        ], 200);

    }
}
