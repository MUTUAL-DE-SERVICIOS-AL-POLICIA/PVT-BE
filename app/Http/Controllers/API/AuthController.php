<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\Affiliate;
use Muserpol\Models\AffiliateDevice;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Http\Requests\API\AuthForm;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Helpers\Util;
use Carbon\Carbon;
use Muserpol\Models\EconomicComplement\EconomicComplement;

class AuthController extends Controller
{
    private function getToken($device_id) {
        do {
            $token = Hash::make($device_id);
        } while(AffiliateDevice::where('api_token', $token)->exists());
        return $token;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')
            ->leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
            ->orderBYDesc('eco_com_procedures.year')
            ->orderBYDesc('eco_com_procedures.semester')
            ->whereAffiliateId($request->affiliate->id)->first();

        return response()->json([
            'error' => false,
            'message' => 'Usuario actual',
            'data' => [
                'api_token' => $request->affiliate->device->api_token,
                'user' => [
                    'id' => $request->affiliate->id,
                    'full_name' => $eco_com_beneficiary->fullName(),
                    'degree' => $request->affiliate->degree->name,
                    'identity_card' => $eco_com_beneficiary->identity_card,
                    'pension_entity' => $request->affiliate->pension_entity->name,
                    'category' => $request->affiliate->category->name,
                    'enrolled' => $request->affiliate->device->enrolled,
                    'verified' => $request->affiliate->device->verified,
                ],
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthForm $request)
    {
        $identity_card = mb_strtoupper($request->identity_card);
        $birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');
        $device_id = $request->device_id;
        if (Util::isDoblePerceptionEcoCom($identity_card)) {
            return response()->json([
                'error' => true,
                'message' => 'Usted percibe el Beneficio como Titular y Viuda(o), por lo cual para realizar el registro de su trámite debe apersonarse por oficinas de la MUSERPOL',
                'data' => (object)[]
            ], 403);
        } else {
            $eco_com_beneficiary = EcoComBeneficiary::whereIdentityCard($identity_card)->whereBirthDate($birth_date)->first();
            if ($eco_com_beneficiary) {
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate;
                $last_eco_com = $affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) {
                    $q->orderBy('year')->orderBy('normal_start_date');
                })->latest()->first();
                if (mb_strtoupper($last_eco_com->eco_com_beneficiary->identity_card) == $identity_card && Carbon::createFromFormat('d/m/Y', $last_eco_com->eco_com_beneficiary->birth_date)->format('Y-m-d') == $birth_date) {
                    $eco_com_beneficiary = $last_eco_com->eco_com_beneficiary;
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Datos de beneficiario incorrectos',
                        'data' => (object)[]
                    ], 403);
                }
                $affiliate_device = AffiliateDevice::whereDeviceId($device_id)->first();
                $token = null;
                if (!$affiliate->device && !$affiliate_device) {
                    $token = $this->getToken($device_id);
                    $affiliate->device()->create([
                        'api_token' => $token,
                        'device_id' => $device_id,
                    ]);
                    $affiliate->device = (object)[
                        'enrolled' => false,
                        'verified' => false
                    ];
                } elseif ($affiliate_device && $affiliate) {
                    if ($affiliate->id == $affiliate_device->affiliate_id) {
                        $token = $this->getToken($device_id);
                        $affiliate->device()->update([
                            'api_token' => $token,
                        ]);
                    }
                }
                if ($token) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Usuario autenticado',
                        'data' => [
                            'api_token' => $token,
                            'user' => [
                                'id' => $affiliate->id,
                                'full_name' => $eco_com_beneficiary->fullName(),
                                'degree' => $affiliate->degree->name,
                                'identity_card' => $eco_com_beneficiary->ciWithExt(),
                                'pension_entity' => $affiliate->pension_entity ? $affiliate->pension_entity->name : '',
                                'category' => $affiliate->category->name,
                                'enrolled' => $affiliate->device->enrolled,
                                'verified' => $affiliate->device->verified,
                            ],
                        ]
                    ], 200);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Dispositivo Inválido',
                        'data' => (object)[]
                    ], 403);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Usted no se encuentra registrado como Beneficiario Habitual, para mayor información pasar por oficinas de la MUSERPOL',
                    'data' => (object)[]
                ], 403);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\Models\AffiliateDevice  $affiliateDevice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->affiliate->device()->update(['api_token' => null]);
        return response()->json([
            'error' => false,
            'message' => 'Sesión terminada',
            'data' => (object)[]
        ], 200);
    }
}
