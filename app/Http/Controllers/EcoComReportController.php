<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Maatwebsite\Excel\Facades\Excel;
use Muserpol\Exports\EcoComReports;
use Muserpol\Models\ObservationType;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Exports\AffiliateReport;
use Muserpol\Exports\AffiliateObservationsReport;
use Muserpol\Exports\EcoComObservationReport;
use Muserpol\Exports\EcoComCompareReport;

class EcoComReportController extends Controller
{
    public function index()
    {
        $eco_com_procedures = EcoComProcedure::orderByDesc('year')->orderByDesc('semester')->get();
        $observation_types = ObservationType::whereIn('type', ["T", "AT"])->get();
        $affiliate_observations = ObservationType::whereIn('type', ["A", "AT"])->get();
        $wf_states = WorkflowState::where('module_id', 2)->get();
        foreach ($eco_com_procedures as $e) {
            $e->full_name = $e->fullName();
        }
        $data = [
            'eco_com_procedures' => $eco_com_procedures,
            'affiliate_observations' => $affiliate_observations,
            'observation_types' => $observation_types,
            'wf_states' => $wf_states,
        ];
        return view('eco_com.report.index', $data);
    }
    public function generate(Request $request)
    {
        logger($request->all());
        $eco_com_procedure = EcoComProcedure::find($request->ecoComProcedureId);
        $data = null;
        switch ($request->reportTypeId) {
            case 1:
            case 2:
            case 3:
            case 8:
                return Excel::download(new EcoComReports($eco_com_procedure->id, $request->reportTypeId, [], []), 'Reporte.xlsx');
                break;
            case 4:
                return Excel::download(new EcoComObservationReport($eco_com_procedure->id), 'Reporte.xlsx');
                break;
            case 6:
            case 7:
                $wf_states_ids = collect($request->wfCurrentStateIds)->pluck('id');
                return Excel::download(new EcoComReports($eco_com_procedure->id, $request->reportTypeId, [], $wf_states_ids), 'Reporte.xlsx');
                break;
            case 9:
                return Excel::download(new AffiliateObservationsReport(), 'Reporte.xlsx');
                break;
            case 10:
            case 11:
            case 12:
            case 13:
                $second_eco_com_procedure = EcoComProcedure::find($request->secondEcoComProcedureId);
                return Excel::download(new EcoComCompareReport($request->reportTypeId,$eco_com_procedure->id, $second_eco_com_procedure->id), 'Reporte.xlsx');
                break;
            default:
                # code...
                break;
        }
        logger("fin");
        return $data;
    }
}