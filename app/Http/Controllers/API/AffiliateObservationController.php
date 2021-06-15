<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\Affiliate;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;

class AffiliateObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Affiliate $affiliate)
    {
        if ($request->affiliate->id == $affiliate->id) {
            $last_eco_com = $affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) {
                $q->orderBy('year')->orderBy('normal_start_date');
            })->latest()->first();
            if (!$last_eco_com || !$last_eco_com->eco_com_beneficiary) {
                return response()->json([
                    'error' => true,
                    'message' => 'No es beneficiario habitual',
                    'data' => []
                ], 401);    
            }
            $observations = array_unique($affiliate->observations()->where('description', 'Denegado')->where('enabled', false)->whereNull('deleted_at')->pluck('shortened')->all());
            $enabled = (count($observations) == 0);
            $available_procedures = EcoComProcedure::affiliate_available_procedures($affiliate->id)->count();
            $data = [];
            if ($enabled) {
                if ($available_procedures == 0) {
                    $enabled = false;
                    $message = 'Ya realizó la solicitud correspondiente al semestre';
                } else {
                    $latest_procedures = EcoComProcedure::orderByDesc('year')->orderByDesc('normal_start_date')->limit(2)->whereNotIn('id', EcoComProcedure::current_procedures()->pluck('id'))->pluck('id');
                    $latest_procedures = $affiliate->economic_complements()->whereIn('eco_com_procedure_id', $latest_procedures)->count();
                    if ($latest_procedures < 1) {
                        $enabled = false;
                        $message = 'Para realizar el registro de su Trámite pase por oficinas de la MUSERPOl';
                    } else {
                        $message = $available_procedures.' solicitud(es) de trámite disponible(s)';
                    }
                }
            } else {
                $data[] = [
                    'key' => 'Observaciones del beneficiario',
                    'value' => $observations,
                ];
                $message = 'No puede solicitar trámites debido a la(s) observación(es)';
            }
            
            return response()->json([
                'error' => false,
                'message' => $message,
                'data' => [
                    'display' => $data,
                    'title' => $last_eco_com->eco_com_beneficiary->fullName(),
                    'subtitle' => 'Beneficiario',
                    'enabled' => $enabled
                ]
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error de autenticación',
                'data' => []
            ], 401);
        }
    }
}
