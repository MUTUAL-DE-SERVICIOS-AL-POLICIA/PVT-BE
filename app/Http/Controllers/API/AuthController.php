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
use Muserpol\Models\EconomicComplement\EcoComModality;
use Muserpol\Models\AffiliateToken;
use Illuminate\Support\Facades\Log;

use Muserpol\Http\Controllers\API\EconomicComplementController as APIEconomicComplementController;

class AuthController extends Controller
{
    private function getToken($device_id)
    {
        do {
            $token = Hash::make($device_id);
        } while (AffiliateToken::where('api_token', $token)->exists());
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
                'api_token' => $request->affiliate->affiliate_token->api_token,
                'user' => [
                    'id' => $request->affiliate->id,
                    'full_name' => $eco_com_beneficiary->fullName(),
                    'degree' => $request->affiliate->degree->name,
                    'identity_card' => $eco_com_beneficiary->identity_card,
                    'pension_entity' => $request->affiliate->pension_entity->name,
                    'category' => $request->affiliate->category->name,
                    'enrolled' => $request->affiliate->affiliate_token->affiliate_device->enrolled,
                    'verified' => $request->affiliate->affiliate_token->affiliate_device->verified,
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
        //$device_id = $request->device_id;
        $firebase_token = $request->firebase_token;
        $is_new_app = isset($request->is_new_app) ? $request->is_new_app : false;
        $is_new_version = isset($request->is_new_version) ? $request->is_new_version : false;
        $is_doble_perception = false;

        if ($is_new_app && $is_new_version) {
            if (Util::isDoblePerceptionEcoCom($identity_card)) {
                $is_doble_perception = true;
                $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')->whereIdentityCard($identity_card)->whereBirthDate($birth_date)->whereIn('eco_com_modality_id', [1, 4, 8, 6])->first();
            } else {
                $eco_com_beneficiary = EcoComBeneficiary::whereIdentityCard($identity_card)->whereBirthDate($birth_date)->first();
            }

            if ($eco_com_beneficiary) {
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate;

                $last_eco_com = $affiliate->economic_complements()->whereHas('eco_com_procedure', function ($q) {
                    $q->orderBy('year')->orderBy('normal_start_date');
                })->latest()->first();

                if (mb_strtoupper($last_eco_com->eco_com_beneficiary->identity_card) == $identity_card && Carbon::createFromFormat('d/m/Y', $last_eco_com->eco_com_beneficiary->birth_date)->format('Y-m-d') == $birth_date) {
                    $economic_beneficiary = $last_eco_com->eco_com_beneficiary; //Datos del Último beneficiario registrado
                    $economic_complement = EconomicComplement::find($economic_beneficiary->economic_complement_id);
                    $eco_com_modality_id = $economic_complement->eco_com_modality_id;
                    $modality_id = EcoComModality::find($eco_com_modality_id);
                    /*Valida que no puedan ingresar los beneficiarios sí es Vejez=29 y está fallecido o es viudedad=30 y es fallecido */
                    if (($modality_id->procedure_modality_id == 29 && $affiliate->dead == true) || ($modality_id->procedure_modality_id == 30 && $affiliate->spouse->first()->dead == true)) {
                        return response()->json([
                            'error' => true,
                            'message' => 'Datos del beneficiario se encuentra registrado como fallecido.',
                            'data' => (object)[]
                        ], 403);
                    } else {
                        $eco_com_beneficiary = $economic_beneficiary;
                    }
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Datos de beneficiario incorrectos.',
                        'data' => (object)[]
                    ], 403);
                }

                //revisar firebase token
                $affiliate_token = AffiliateToken::whereAffiliateId($affiliate->id)->first();
                $incomming_firebase = AffiliateToken::whereFirebaseToken($firebase_token)->latest()->first();  // get()
                if (!is_null($incomming_firebase)) {
                    if (!is_null($incomming_firebase->firebase_token)) {
                        $incomming_firebase->firebase_token = null;
                        $incomming_firebase->update();
                    }
                }

                $token = null;
                if (!$affiliate_token) { // Primer ingreso
                    $token = $this->getToken($affiliate->id);
                    $affiliate_token = $affiliate->affiliate_token()->create([
                        'api_token' => $token,
                    ]);
                    $affiliate_device = new AffiliateDevice;
                    $device = $affiliate_device->create([
                        'affiliate_token_id' => $affiliate_token->id,
                        'enrolled' => false,
                        'verified' => false
                    ]);
                } else {

                    $token = $this->getToken($affiliate->id);
                    $update = [
                        'api_token' => $token,
                    ];
                    $affiliate->affiliate_token()->update($update);

                    if ($affiliate_token->affiliate_device) {



                        if ($affiliate_token->affiliate_device->enrolled) {
                            $affiliate_token->firebase_token = $firebase_token;
                            $affiliate_token->update();
                        }

                        $device = (object)[];
                        $device->enrolled = $affiliate_token->affiliate_device->enrolled;
                        $device->verified = $affiliate_token->affiliate_device->verified;
                    } else {

                        $affiliate_device = new AffiliateDevice;
                        $device = $affiliate_device->create([
                            'affiliate_token_id' => $affiliate_token->id,
                            'enrolled' => false,
                            'verified' => false
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
                                'enrolled' => $device->enrolled,
                                'verified' => $device->verified,
                            ],
                            'is_doble_perception' => $is_doble_perception
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
                    'message' => 'Usted no se encuentra registrado como Beneficiario Habitual, para mayor información pasar por oficinas de la MUSERPOL.',
                    'data' => (object)[]
                ], 403);
            }
            //}
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Para continuar y mejorar el servicio, usted debe actualizar la Aplicación Móvil',
                'data' => (object)[]
            ], 403);
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
        $request->affiliate->affiliate_token()->update(['api_token' => null, 'firebase_token' => null]);
        return response()->json([
            'error' => false,
            'message' => 'Sesión terminada',
            'data' => (object)[]
        ], 200);
    }
}
