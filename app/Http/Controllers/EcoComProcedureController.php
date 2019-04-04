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
        Log::info("**********");
        Log::info($request->id);
        $eco_com_procedure_ids = Util::getEcoComCurrentProcedure();
        Log::info($eco_com_procedure_ids);
        if (!$eco_com_procedure_ids) {
            return response()->json([], 204);
        }
        $affiliate = Affiliate::find($request->affiliate_id);
        if ($request->id) {
            $index = $eco_com_procedure_ids->search($request->id);
            if ($index === false) {
                // retornar activo;
                if($affiliate->canCreateEcoComProcedure(end($eco_com_procedure_ids))){
                    Log::info('ultimo1 '.$eco_com_procedure_ids->last());
                    return EcoComProcedure::find($eco_com_procedure_ids->last());
                }
                if($affiliate->canCreateEcoComProcedure($eco_com_procedure_ids->first())){
                    Log::info('primero1 '.$eco_com_procedure_ids->first());
                    return EcoComProcedure::find($eco_com_procedure_ids->first());
                }
                return response()->json([], 204);
            }
            $eco_com_procedure = EcoComProcedure::find($request->id);
            $next_eco_com_procedure= $eco_com_procedure->getNextProcedure();
            if ($next_eco_com_procedure) {
                if($affiliate->canCreateEcoComProcedure($next_eco_com_procedure->id)){
                    Log::info("entree1 ".$next_eco_com_procedure->id);
                    return $next_eco_com_procedure;
                }else{
                    Log::info("vacio1 ");
                    return response()->json([], 204);
                }
            }else{
                Log::info("vacio2 ");
                return response()->json([], 204);
            }
        }
        if($affiliate->canCreateEcoComProcedure($eco_com_procedure_ids->last())){
            Log::info('ultimo2 '.$eco_com_procedure_ids->last());
            return EcoComProcedure::find($eco_com_procedure_ids->last());
        }
        if($affiliate->canCreateEcoComProcedure($eco_com_procedure_ids->first())){
            Log::info('primero2 '.$eco_com_procedure_ids->first());
            return EcoComProcedure::find($eco_com_procedure_ids->first());
        }
        return response()->json([], 204);
        Log::info("NOOOOOOOOOOOOO");
    }
}
