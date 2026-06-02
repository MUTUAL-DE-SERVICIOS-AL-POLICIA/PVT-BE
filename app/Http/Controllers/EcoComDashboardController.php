<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Illuminate\Support\Facades\DB;
use Muserpol\Helpers\Util;
use Carbon\Carbon;

class EcoComDashboardController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
    }
    public function modalitiesGeneral()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
            ->leftJoin('procedure_modalities', 'procedure_modalities.id', '=', 'eco_com_modalities.procedure_modality_id')
            ->whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
            ->select(DB::raw('count(*) as quantity,procedure_modalities.name as name'))
            ->groupBy('procedure_modalities.name')
            ->get();
        return $eco_coms;
    }
    public function modalities()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
            ->whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
            ->select(DB::raw('count(*) as quantity,eco_com_modalities.name as name'))
            ->groupBy('eco_com_modalities.name')
            ->get();
        return $eco_coms;
    }
    public function cities()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::leftJoin('cities', 'economic_complements.city_id', '=', 'cities.id')
            ->whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
            ->select(DB::raw('count(*) as quantity,cities.name as name'))
            ->groupBy('cities.name')
            ->get();
        return $eco_coms;
    }
    public function receptionType()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::leftJoin('eco_com_reception_types', 'economic_complements.eco_com_reception_type_id', '=', 'eco_com_reception_types.id')
            ->whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
            ->select(DB::raw('count(*) as quantity,eco_com_reception_types.name as name'))
            ->groupBy('eco_com_reception_types.name')
            ->get();
        return $eco_coms;
    }
    public function pensionEntity()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
            ->leftJoin('pension_entities', 'affiliates.pension_entity_id', '=', 'pension_entities.id')
            ->whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
            ->select(DB::raw('count(*) as quantity,pension_entities.type as name'))
            ->groupBy('pension_entities.type')
            ->get();
        return $eco_coms;
    }
    public function states()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::leftJoin('eco_com_states', 'economic_complements.eco_com_state_id', '=', 'eco_com_states.id')
            ->whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
            ->select(DB::raw('count(*) as quantity,eco_com_states.name as name'))
            ->groupBy('eco_com_states.name')
            ->get();
        return $eco_coms;
    }
    // Estado de los Tramites General
    // public function stateTypes()
    // {
    //     $eco_com_procedure = EcoComProcedure::find(request()->id);
    //     if (!$eco_com_procedure) {
    //         return [];
    //     }
    //     $eco_coms = EconomicComplement::leftJoin('eco_com_states', 'economic_complements.eco_com_state_id', '=', 'eco_com_states.id')
    //         ->leftJoin('eco_com_state_types', 'eco_com_state_types.id', '=', 'eco_com_states.eco_com_state_type_id')
    //         ->whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
    //         ->select(DB::raw('count(*) as quantity,eco_com_state_types.name as name'))
    //         ->groupBy('eco_com_state_types.name')
    //         ->get();
    //     return $eco_coms;
    // }

    public function creationEcoCom()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::selectRaw("COUNT(economic_complements.id) AS quantity, COALESCE(eco_com_origin_channel.name, 'No Definido') as name")
            ->leftJoin('eco_com_origin_channel', 'economic_complements.eco_com_origin_channel_id', '=', 'eco_com_origin_channel.id')
            ->where('economic_complements.eco_com_procedure_id', $eco_com_procedure->id)
            ->whereNull('economic_complements.deleted_at')
            ->groupBy('name')
            ->get();
        return $eco_coms;
    }    
    public function wfStates()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::leftJoin('wf_states', 'economic_complements.wf_current_state_id', '=', 'wf_states.id')
            ->whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
            ->select(DB::raw("sum(case when economic_complements.inbox_state = true then 1 else 0 end) as validados, sum(case when economic_complements.inbox_state = false then 1 else 0 end) as pendientes, wf_states.first_shortened as name, wf_states.sequence_number"))
            ->groupBy('wf_states.first_shortened', 'wf_states.sequence_number')
            ->orderBy('wf_states.sequence_number')
            ->get();
        return $eco_coms;
    }
    public function lastEcoCom()
    {
        $years = request()->years;
        if (!$years) {
            $years = 5;
        }
        $eco_com_procedures = EcoComProcedure::orderByDesc('year')->orderByDesc('semester')->take($years)->get()->reverse();
        $results = collect([]);
        foreach ($eco_com_procedures as $e) {
            $results->push([
                'quantity' => $e->economic_complements->count(),
                'name' => $e->getTextName(),
            ]);
        }
        return $results;
    }
    public function totalAmountLastEcoCom()
    {
        $years = request()->years;
        if (!$years) {
            $years = 5;
        }
        $eco_com_procedures = EcoComProcedure::orderByDesc('year')->orderByDesc('semester')->take($years)->get()->reverse();
        $results = collect([]);
        foreach ($eco_com_procedures as $e) {
            $sum_total_semester = 0;
            $sum_total_semester = $e->economic_complements->sum(function ($eco_com) {
                return round($eco_com->total_amount_semester * round($eco_com->complementary_factor/100,3),2);
            });
            $results->push([
                'quantity' => $sum_total_semester,
                'name' => $e->getTextName(),
            ]);
        }
        return $results;
    }
    public function receptionMonths()
    {
        $eco_com_procedure = EcoComProcedure::find(request()->id);
        if (!$eco_com_procedure) {
            return [];
        }
        $eco_coms = EconomicComplement::whereIn('economic_complements.eco_com_procedure_id', [$eco_com_procedure->id])
            ->select(DB::raw("count(*) as quantity,date_trunc('day', reception_date) as name"))
            ->groupBy('name')
            ->orderBy('name')
            ->get();
        $results = collect([]);
        foreach ($eco_coms as $e) {
            $results->push([
                'quantity' => $e->quantity,
                'name' => Carbon::parse($e->name)->formatLocalized("%b %Y"),
            ]);
        }
        return $results;
    }
}
