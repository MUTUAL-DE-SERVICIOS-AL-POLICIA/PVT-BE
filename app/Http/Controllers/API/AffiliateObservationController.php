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
            $observations = array_unique($affiliate->observations()->where('description', 'Denegado')->where('enabled', false)->pluck('shortened')->all());
            $enabled = (count($observations) == 0);
            $has_observations = count($observations) > 0;
            $available_procedures = EcoComProcedure::affiliate_available_procedures($affiliate->id)->count();
            if ($enabled) {
                if ($available_procedures == 0) {
                    $enabled = false;
                }
            }

            return response()->json([
                'error' => false,
                'message' => $has_observations ? 'No puede solicitar trámites debido a la(s) observación(es)' : ($available_procedures == 0 ? 'Ya realizó la solicitud correspondiente al semestre' : $available_procedures.' solicitud(es) de trámite disponible(s)'),
                'data' => [
                    'display' => [
                        [
                            'key' => 'Obs. de afiliado',
                            'value' => $has_observations ? $observations : 'Ninguna',
                        ],
                    ],
                    'title' => $last_eco_com->eco_com_beneficiary->fullName(),
                    'subtitle' => '',
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
