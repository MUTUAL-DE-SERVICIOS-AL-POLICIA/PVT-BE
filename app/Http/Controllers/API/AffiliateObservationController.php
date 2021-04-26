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
            $observations = array_unique($affiliate->observations()->where('description', 'Denegado')->pluck('shortened')->all());
            $enabled = (count($observations) == 0);
            if ($enabled) {
                if (EcoComProcedure::affiliate_available_procedures($affiliate->id)->count() == 0) {
                    $enabled = false;
                }
            }

            return response()->json([
                'error' => false,
                'message' => 'Observaciones de afiliado',
                'data' => [
                    'display' => [
                        [
                            'key' => 'Habilitado',
                            'value' => $enabled ? 'Si' : 'No',
                        ], [
                            'key' => 'Obs. de afiliado',
                            'value' => $enabled ? 'Ninguna' : $observations,
                        ],
                    ],
                    'title' => $affiliate->fullNameWithDegree(),
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
