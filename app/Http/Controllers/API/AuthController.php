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
    private function getToken($device_id) {
        do {
            $token = Hash::make($device_id);
        } while(AffiliateToken::where('api_token', $token)->exists());
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
        $device_id = $request->device_id;
        $firebase_token = $request->firebase_token;
        $is_new_app= isset( $request->is_new_app ) ? $request->is_new_app : false;
        $is_new_version= isset( $request->is_new_version ) ? $request->is_new_version : false;
        $update_device_id = false;
        $code = 200;
        $is_doble_perception = false;

        if($is_new_app && $is_new_version){
            /*if (Util::isDoblePerceptionEcoCom($identity_card)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Usted percibe el Beneficio como Titular y Viuda(o), por lo cual para realizar el registro de su trámite debe apersonarse por oficinas de la MUSERPOL.',
                    'data' => (object)[]
                ], 403);
            } else {*/
                if (Util::isDoblePerceptionEcoCom($identity_card)){
                    $is_doble_perception = true;
                    $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')->whereIdentityCard($identity_card)->whereBirthDate($birth_date)->whereIn('eco_com_modality_id',[1,4,8,6])->first();
                }else{
                    $eco_com_beneficiary = EcoComBeneficiary::whereIdentityCard($identity_card)->whereBirthDate($birth_date)->first();
                }
                if ($eco_com_beneficiary) {
                    $affiliate = $eco_com_beneficiary->economic_complement->affiliate;
                    $affiliate_device = AffiliateDevice::whereDeviceId($device_id)->first();

                    if ($affiliate_device) {
                        $affiliate_token  = $affiliate_device->affiliate_token;
                        if ($affiliate_token->affiliate_id != $affiliate->id) { 
                            return response()->json([
                                'error' => true,
                                'message' => 'Este dispositivo está registrado con otro usuario.',
                                'data' => (object)[]
                            ], 403);
                        }
                    }
                    $last_eco_com = $affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) {
                        $q->orderBy('year')->orderBy('normal_start_date');
                    })->latest()->first();
                    
                    if (mb_strtoupper($last_eco_com->eco_com_beneficiary->identity_card) == $identity_card && Carbon::createFromFormat('d/m/Y', $last_eco_com->eco_com_beneficiary->birth_date)->format('Y-m-d') == $birth_date) {
                        $economic_beneficiary = $last_eco_com->eco_com_beneficiary; //Datos del Último beneficiario registrado
                        $economic_complement = EconomicComplement::find($economic_beneficiary->economic_complement_id);
                        $eco_com_modality_id = $economic_complement->eco_com_modality_id;
                        $modality_id = EcoComModality::find($eco_com_modality_id);
                        /*Valida que no puedan ingresar los beneficiarios sí es Vejez=29 y está fallecido o es viudedad=30 y es fallecido */
                           if(($modality_id->procedure_modality_id == 29 && $affiliate->dead == true ) || ($modality_id->procedure_modality_id == 30 && $affiliate->spouse->first()->dead == true)) {
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
                    $affiliate_token = AffiliateToken::whereAffiliateId($affiliate->id)->first();
                    $incomming_firebase = AffiliateToken::whereFirebaseToken($firebase_token)->latest()->first();  // get()
                    if(!is_null($incomming_firebase)) {
                        if(!is_null($incomming_firebase->firebase_token)) {
                        $incomming_firebase->firebase_token = null; 
                        $incomming_firebase->update();
                      }
                    }

                    $token = null;
                    if (!$affiliate->affiliate_token && !$affiliate_token) { // Primer ingreso
                        $token = $this->getToken($device_id);
                        $affiliate_token = $affiliate->affiliate_token()->create([
                            'api_token' => $token,
                        ]);
                        $affiliate_device = new AffiliateDevice;
                        $device = $affiliate_device->create([
                            'affiliate_token_id' => $affiliate_token->id,
                            'enrolled' => false,
                            'verified' => false
                        ]);
                    } elseif ($affiliate_token && $affiliate) {  // Segunda vez o más o por oficina virtual 
                        if ($affiliate_token->affiliate_device) { 
                            if ($device_id == $affiliate_token->affiliate_device->device_id || $affiliate_token->affiliate_device->device_id == null) {

                                if($affiliate_token->affiliate_device->device_id == null) {
                                    if($affiliate_token->affiliate_device->enrolled && $affiliate_token->affiliate_device->verified) {
                                        $update_device_id = true;
                                        $token = $this->getToken($device_id); 
                                        $update = [
                                            'api_token' => $token,
                                        ];
                                        $affiliate->affiliate_token()->update($update);
                                    } elseif($affiliate_token->affiliate_device->enrolled == false && $affiliate_token->affiliate_device->verified == false){
                                        $token = $this->getToken($device_id); 
                                        $update = [
                                            'api_token' => $token,
                                        ];
                                        $affiliate->affiliate_token()->update($update);
                                    } else {
                                        $update = [
                                            'api_token' => null
                                        ];
                                        $affiliate->affiliate_token()->update($update);
                                    }
                                } else {
                                    $token = $this->getToken($device_id); 
                                    $update = [
                                        'api_token' => $token,
                                    ];
                                    $affiliate->affiliate_token()->update($update);
                                }
                                $var = $affiliate_token->affiliate_device;
                                if($var->enrolled && !is_null($token) && !$update_device_id) {
                                    $affiliate_token->firebase_token = $firebase_token;
                                    $affiliate_token->update();
                                } 

                                $device = (object)[];
                                $device->enrolled = $affiliate_token->affiliate_device->enrolled;
                                $device->verified = $affiliate_token->affiliate_device->verified;
                            } elseif ($device_id != $affiliate_token->affiliate_device->device_id) {
                                $device = (object)[];
                                if($affiliate_token->affiliate_device->enrolled == true && $affiliate_token->affiliate_device->verified == true) {
                                    $update_device_id = $device->enrolled = $device->verified = true;
                                    $token = $this->getToken($device_id);
                                    $update = [
                                        'api_token' => $token,
                                    ];
                                    $affiliate->affiliate_token()->update($update);
                                } elseif ($affiliate_token->affiliate_device->enrolled == false && $affiliate_token->affiliate_device->verified == false) {
                                    $update_device_id = $device->enrolled = $device->verified = false;
                                    $token = $this->getToken($device_id);
                                    $update = [
                                        'api_token' => $token,
                                    ];
                                    $affiliate->affiliate_token()->update($update);
                                } else {
                                   $update = [
                                    'api_token' => null
                                   ];
                                    $affiliate->affiliate_token()->update($update);
                                }
                            }
                        } elseif(AffiliateDevice::find($affiliate_token->id) == null) {  // Entro a la oficina virtual y ahora quiere ingresar a CE
                            $token = $this->getToken($device_id);
                            $update = [
                                'api_token' => $token,
                            ];
                            $affiliate->affiliate_token()->update($update);
                            $affiliate_device = new AffiliateDevice;
                            $device = $affiliate_device->create([
                                'affiliate_token_id' => $affiliate_token->id,
                                'enrolled' => false,
                                'verified' => false
                            ]);
                        }
                    }
                    $code = $update_device_id ? 201: 200;
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
                                'update_device_id' => $update_device_id,
                                'is_doble_perception' => $is_doble_perception
                            ]
                        ], $code);
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
        }else{
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

    public function kioskoComplemento(Request $request)
    {
        $token = $request->bearerToken();
        if (Hash::check('kiosko-muserpol', $token)) {
            $affiliate = Affiliate::where('identity_card', $request->ci)->first();
            if (!$affiliate) {
                return response()->json([
                    'error' => true,
                    'message' => 'No existe afiliado',
                    'data' => (object)[]
                ], 404);
            }
            return APIEconomicComplementController::checkEcoComAvailability($affiliate);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Sin Autorización',
                'data' => (object)[]
            ], 403);
        }
    }
}
