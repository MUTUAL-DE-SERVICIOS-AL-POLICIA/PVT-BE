<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureRequirement;
use Illuminate\Support\Facades\DB;

class AffiliateSubmittedDocumentsController extends Controller
{
    public function getRequirements(Request $request)
    {
        $affiliate = Affiliate::find($request->affiliate_id);
        if ($request->reception_type_id == 2) {
            $procedure_requirements_original = ProcedureRequirement::with('procedure_document')->where('procedure_modality_id', $request->procedure_modality_id)->where('number', '<>', 0)->orderBy('number')->get();
            $additional_requirements = ProcedureRequirement::with('procedure_document')->where('procedure_modality_id', $request->procedure_modality_id)->where('number', '=', 0)->orderBy('number')->get();
            
            $requirements = collect([]);
            foreach ($procedure_requirements_original as $po) {
                    $po->background = "";
                    $po->status = false;
                    $po->number = "N" . $po->number;
                    $requirements->push($po);
            }
            $requirements = $requirements->groupBy('number')->toArray();
            $data = [
                'additional_requirements' => $additional_requirements,
                'requirements' => $requirements
            ];
            return $data;
        } else {
            $requirements_habitual = [];
            switch ($request->procedure_modality_id) {
                case 29:
                    $requirements_habitual = [1235];
                    break;
                case 30:
                    //$requirements_habitual = [1263, 1262, 1264, 1266];
                    $requirements_habitual = [1266];
                    break;
                case 31:
                    $requirements_habitual = [1298, 1297, 1300];
                    break;
                default:
                    # code...
                    break;
            }
            $procedure_requirements_original = ProcedureRequirement::with('procedure_document')
                // ->where('procedure_modality_id', $request->procedure_modality_id)
                ->where('number', '<>', 0)
                ->whereIn('id', $requirements_habitual)
                ->orderBy('number')->get();
            $additional_requirements = ProcedureRequirement::with('procedure_document')
                ->where('procedure_modality_id', $request->procedure_modality_id)
                ->where('number', '=', 0)
                ->orderBy('number')->get();
            $requirements = collect([]);
            foreach ($procedure_requirements_original as $po) {
                $po->background = "";
                $po->status = false;
                $po->number = "N" . $po->number;
                $requirements->push($po);
            }
            $requirements = $requirements->groupBy('number')->toArray();
            $data = [
                'additional_requirements' => $additional_requirements,
                'requirements' => $requirements
            ];
            return $data;
        }
    }
}