<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Helpers\Util;
use Muserpol\Models\Affiliate;

class EcoComProcedureController extends Controller
{
    public function getProcedureActives(Request $request)
    {
        $eco_com_procedure_ids = Util::getEcoComCurrentProcedure();
        $eco_com_procedure_last = EcoComProcedure::find($eco_com_procedure_ids[0]);
        $eco_com_procedure_before_last = EcoComProcedure::find($eco_com_procedure_ids[1]);
        if ($request->id) {
            $affiliate = Affiliate::find($request->affiliate_id);
            if (!$affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_last->id) &&  !$affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_before_last->id)) {
                return $eco_com_procedure_before_last;
            }
            if (!$affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_last->id) &&  $affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_before_last->id)) {
                return $eco_com_procedure_last;
            }
            // esto no deberia pasar 
            if ($affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_last->id) &&  !$affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_before_last->id)) {
                return $eco_com_procedure_before_last;
            }
            if ($affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_last->id) &&  $affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_before_last->id)) {
                return response()->json([], 204);
            }
        }
        return $eco_com_procedure_before_last;
    }
}
