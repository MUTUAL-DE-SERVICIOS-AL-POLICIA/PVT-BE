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
use Muserpol\Exports\AffiliateTagsReport;
use Muserpol\Exports\EcoComStateReport;
use Muserpol\Exports\EcoComTagsReport;
use Muserpol\Exports\EcoComPlanillaGeneralReport;
use Muserpol\Exports\EcoComBankExport;
use Muserpol\Exports\AffiliateSpouseReport;
use Muserpol\Exports\EcoComPromedioReport;

use Muserpol\Exports\EcoComPlanillaGeneralPagos;
use Muserpol\Exports\EcoComOverpaymentsSheet;
use Muserpol\Exports\AffiliateNoScanner;
use Muserpol\Exports\AffiliateDoublePerception;
use Muserpol\Exports\EcoComDocumentManagementReport;

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
        $eco_com_procedure = EcoComProcedure::find($request->ecoComProcedureId);
        $data = null;
        switch ($request->reportTypeId) {
            case 1:
            case 2:
            case 3:
            case 5:
            case 8:
                $wf_states_ids = collect($request->wfCurrentStateIds)->pluck('id');
                return Excel::download(new EcoComReports($eco_com_procedure->id, $request->reportTypeId, [], $wf_states_ids), 'Todos_tramites.xlsx');
                break;
            case 4:
                $wf_states_ids = collect($request->wfCurrentStateIds)->pluck('id');
                return Excel::download(new EcoComObservationReport($eco_com_procedure->id,$wf_states_ids), 'Reporte.xlsx');
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
            case 20:
            case 19:
                $second_eco_com_procedure = EcoComProcedure::find($request->secondEcoComProcedureId);
                return Excel::download(new EcoComCompareReport($request->reportTypeId, $eco_com_procedure->id, $second_eco_com_procedure->id), 'Reporte.xlsx');
                break;
            
            case 14:
                return Excel::download(new AffiliateTagsReport(), 'Reporte.xlsx');
                break;
            case 15:
                $wf_states_ids = collect($request->wfCurrentStateIds)->pluck('id');
                return Excel::download(new EcoComStateReport($eco_com_procedure->id,$wf_states_ids), 'Reporte.xlsx');
                break;
            case 16:
                return Excel::download(new EcoComTagsReport($eco_com_procedure->id), 'Reporte.xlsx');
                break;
            case 17:
                $change_state = $request->changeState;
                return Excel::download(new EcoComPlanillaGeneralReport($eco_com_procedure->id, $change_state), 'Reporte.xlsx');
                break;
            case 18:
                $change_state = $request->changeState;
                return Excel::download(new EcoComBankExport($eco_com_procedure->id, $change_state), 'Reporte.xlsx');
                break;
            case 21:
            case 22:
                return Excel::download(new AffiliateSpouseReport($request->reportTypeId), 'Reporte.xlsx');
                break;
            case 23:
            case 24:
                return Excel::download(new EcoComPromedioReport($request->reportTypeId,$eco_com_procedure->id,$request->changeDate), 'Reporte.xlsx');
                break;
            case 25:
            case 26:
            case 27:
                return Excel::download(new EcoComPlanillaGeneralPagos($request->reportTypeId,$eco_com_procedure->id), 'Reporte.xlsx');
                break;
            case 28:
                return Excel::download(new EcoComOverpaymentsSheet(), 'Pagos en Demasia.xlsx');
                break;
            case 29:
                return Excel::download(new AffiliateNoScanner(), 'Afiliados documentos no escaneados.xlsx');
                break;
            case 30:
                return Excel::download(new AffiliateDoublePerception(), 'Afiliados doble percepcion.xlsx');
                break;
            case 31:
                return Excel::download(new EcoComDocumentManagementReport(), 'Gestion Documental.xlsx');
                break;
            default:
                # code...
                break;
        }
        return $data;
    }
}
