<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\EconomicComplement\EcoComFixedPension;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Http\Resources\EconomicComplementResource;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EcoComStateType;
use Muserpol\Models\EconomicComplement\EcoComModality;
use Muserpol\Models\EconomicComplement\EcoComSubmittedDocument;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\Address;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ObservationType;
use Muserpol\Helpers\Util;
use Muserpol\Helpers\ID;
use Carbon\Carbon;
use Muserpol\Models\Spouse;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\User;
use Ramsey\Uuid\Uuid;

class EconomicComplementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $affiliate_ids = array();
       array_push($affiliate_ids, $request->affiliate->id);
       $last_eco_com = $request->affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) { $q->orderBy('year')->orderBy('normal_start_date'); })->latest()->first();
       $last_eco_com_beneficiary = $last_eco_com->eco_com_beneficiary()->first();

       if (Util::isDoblePerceptionEcoCom($last_eco_com_beneficiary->identity_card)) {
           $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')->whereIdentityCard($last_eco_com_beneficiary->identity_card)->whereIn('eco_com_modality_id',[2,5,9,7])->first();
           $affiliate_widowhood = $eco_com_beneficiary->economic_complement->affiliate;
           array_push($affiliate_ids, $affiliate_widowhood->id);
       }
       $current_procedures = EcoComProcedure::current_procedures()->pluck('id');
       if (filter_var($request->query('current'), FILTER_VALIDATE_BOOLEAN, false)) {
        $state_types = EcoComStateType::whereIn('name', ['Enviado', 'Creado'])->pluck('id');
        $data = EconomicComplement::leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
            ->leftJoin('eco_com_states', 'economic_complements.eco_com_state_id', '=', 'eco_com_states.id')
            ->leftJoin('eco_com_state_types', 'eco_com_state_types.id', '=', 'eco_com_states.eco_com_state_type_id')
            ->where(function($q) use ($current_procedures, $state_types) {
                $q->whereIn('economic_complements.eco_com_procedure_id',$current_procedures)->orWhereHas('eco_com_state', function ($query) use ($state_types) {
                    return $query->whereIn('eco_com_states.eco_com_state_type_id', $state_types);
                });
            })
            ->whereIn('affiliate_id', $affiliate_ids)
            ->select('economic_complements.*')
            ->orderBY('eco_com_procedures.year','DESC')
            ->orderBY('eco_com_procedures.semester','DESC');
      }else{
        $state_types = EcoComStateType::whereIn('name', ['Pagado', 'No Efectivizado'])->pluck('id');
        $data = EconomicComplement::leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
            ->leftJoin('eco_com_states', 'economic_complements.eco_com_state_id', '=', 'eco_com_states.id')
            ->leftJoin('eco_com_state_types', 'eco_com_state_types.id', '=', 'eco_com_states.eco_com_state_type_id')
            ->where(function($q) use ($current_procedures, $state_types) {
                $q->whereNotIn('economic_complements.eco_com_procedure_id',$current_procedures)->WhereHas('eco_com_state', function ($query) use ($state_types) {
                    return $query->whereIn('eco_com_states.eco_com_state_type_id', $state_types);
                });
            })
            ->whereIn('affiliate_id', $affiliate_ids)
            ->select('economic_complements.*')
            ->orderBY('eco_com_procedures.year','DESC')
            ->orderBY('eco_com_procedures.semester','DESC');
      }
        return response()->json([
            'error' => false,
            'message' => 'Complemento Económico',
            'data' => EconomicComplementResource::collection($data->paginate($request->per_page ?? 4, ['*'], 'page', $request->page ?? 1))->resource,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\Models\EconomicComplement\EconomicComplement  $economicComplement
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, EconomicComplement $economicComplement)
    {
        $eco_com_beneficiary = $economicComplement->eco_com_beneficiary;
        $affiliate = $request->affiliate;
        if (Util::isDoblePerceptionEcoCom($eco_com_beneficiary->identity_card)) {
            $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')->whereIdentityCard($eco_com_beneficiary->identity_card)->whereIn('eco_com_modality_id',[2,5,9,7])->first();
            $affiliate = $eco_com_beneficiary->economicComplement->affiliate;
        }
        if ($economicComplement->affiliate_id == $request->affiliate->id || $economicComplement->affiliate_id == $affiliate->id) {
            return response()->json([
                'error' => false,
                'message' => 'Complemento Económico ' . $economicComplement->code,
                'data' => new EconomicComplementResource($economicComplement),
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Este trámite no le pertenece',
                'data' => null,
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\Models\EconomicComplement\EconomicComplement  $economicComplement
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request, EconomicComplement $economic_complement)
    {
        $eco_com_beneficiary = $economic_complement->eco_com_beneficiary;
        $affiliate = $request->affiliate;
        if (Util::isDoblePerceptionEcoCom($eco_com_beneficiary->identity_card)) {
            $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')->whereIdentityCard($eco_com_beneficiary->identity_card)->whereIn('eco_com_modality_id',[2,5,9,7])->first();
            $affiliate = $eco_com_beneficiary->economic_complement->affiliate;
        }
        if ($economic_complement->affiliate_id == $request->affiliate->id || $economic_complement->affiliate_id == $affiliate->id ) {
            return $this->print_pdf($economic_complement);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Este trámite no le pertenece',
                'data' => null,
            ], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $eco_com_procedure_id = $request->eco_com_procedure_id;
        $cell_phone_number = $request->cell_phone_number;        
        $affiliate = $request->affiliate;
        $last_eco_com = $request->affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) { $q->orderBy('year')->orderBy('normal_start_date'); })->latest()->first();
        $last_eco_com_beneficiary = $last_eco_com->eco_com_beneficiary()->first();        
        $now = Carbon::now()->toDateString();
        $verified = $affiliate->affiliate_token->affiliate_device->verified;

        $has_economic_complement = $affiliate->hasEconomicComplementWithProcedure($request->eco_com_procedure_id);
        if (!$has_economic_complement) {
            /**
             ** Create Economic Complement 
            */
            $economic_complement = new EconomicComplement();
            $economic_complement->user_id = 171;
            $economic_complement->affiliate_id = $affiliate->id;
            $economic_complement->eco_com_modality_id = EcoComModality::where('procedure_modality_id','=',$last_eco_com->eco_com_modality->procedure_modality_id)->where('name', 'like', '%normal%')->first()->id;
            $economic_complement->eco_com_state_id = ID::ecoComState()->in_process;
            $economic_complement->eco_com_procedure_id = $eco_com_procedure_id;
            $economic_complement->workflow_id = EcoComProcedure::whereDate('lagging_end_date', '>=', $now)->first()->id? ID::workflow()->eco_com_normal : ID::workflow()->eco_com_additional;
            $economic_complement->wf_current_state_id = $verified ? 1 : 60;
            $economic_complement->city_id = $last_eco_com->city_id;
            $economic_complement->degree_id = $affiliate->degree->id;
            $economic_complement->category_id = $affiliate->category->id;
            $economic_complement->code = Util::getLastCodeEconomicComplement($eco_com_procedure_id);
            $economic_complement->reception_date = now();
            $economic_complement->inbox_state = false;
            $economic_complement->eco_com_reception_type_id = ID::ecoCom()->habitual;
            $economic_complement->uuid = Uuid::uuid1()->toString();
            /*
            if ($affiliate->pension_entity_id == ID::pensionEntity()->senasir) {
                $economic_complement->sub_total_rent = Util::parseMoney($last_eco_com->sub_total_rent);
                $economic_complement->reimbursement = Util::parseMoney($last_eco_com->reimbursement);
                $economic_complement->dignity_pension = Util::parseMoney($last_eco_com->dignity_pension);
                $economic_complement->aps_disability = Util::parseMoney($last_eco_com->aps_disability);
                $economic_complement->aps_total_fsa = null;
                $economic_complement->aps_total_cc = null;
                $economic_complement->aps_total_fs = null;
                $economic_complement->total_rent =
                $economic_complement->sub_total_rent -
                $economic_complement->reimbursement -
                $economic_complement->dignity_pension +
                $economic_complement->aps_disability;
            } else {
                $economic_complement->aps_total_fsa = Util::parseMoney($last_eco_com->aps_total_fsa);
                $economic_complement->aps_total_cc = Util::parseMoney($last_eco_com->aps_total_cc);
                $economic_complement->aps_total_fs = Util::parseMoney($last_eco_com->aps_total_fs);
                $economic_complement->aps_disability = Util::parseMoney($last_eco_com->aps_disability);
                $economic_complement->sub_total_rent = null;
                $economic_complement->reimbaffiliateotal_fsa +
                $economic_complement->aps_total_cc +
                $economic_complement->aps_total_fs +
                $economic_complement->aps_disability;
            }*/
            $economic_complement->save();
            /**
             ** Save eco com beneficiary
            */

            //Desactivar Observers
            EconomicComplement::FlushEventListeners(); 
            $this->updateEcoComWithFixedPension($economic_complement->id);    
            //Activar Observers
            EconomicComplement::Boot();

            $eco_com_beneficiary = new EcoComBeneficiary();
            $eco_com_beneficiary->economic_complement_id = $economic_complement->id;
            $eco_com_beneficiary->city_identity_card_id = $last_eco_com_beneficiary->city_identity_card_id;
            $eco_com_beneficiary->identity_card = $last_eco_com_beneficiary->identity_card;
            $eco_com_beneficiary->last_name = $last_eco_com_beneficiary->last_name;
            $eco_com_beneficiary->mothers_last_name = $last_eco_com_beneficiary->mothers_last_name;
            $eco_com_beneficiary->first_name = $last_eco_com_beneficiary->first_name;
            $eco_com_beneficiary->second_name = $last_eco_com_beneficiary->second_name;
            $eco_com_beneficiary->surname_husband = $last_eco_com_beneficiary->surname_husband;
            $eco_com_beneficiary->birth_date = Util::verifyBarDate($last_eco_com_beneficiary->birth_date) ? Util::parseBarDate($last_eco_com_beneficiary->birth_date) : $last_eco_com_beneficiary->birth_date;
            $eco_com_beneficiary->nua = $last_eco_com_beneficiary->nua;
            $eco_com_beneficiary->gender = $last_eco_com_beneficiary->gender;
            $eco_com_beneficiary->civil_status = $last_eco_com_beneficiary->civil_status;
            $eco_com_beneficiary->phone_number = $last_eco_com_beneficiary->phone_number;
            $eco_com_beneficiary->cell_phone_number = $cell_phone_number;
            $eco_com_beneficiary->city_birth_id = $last_eco_com_beneficiary->city_birth_id;
            $eco_com_beneficiary->due_date = Util::verifyBarDate($last_eco_com_beneficiary->due_date) ? Util::parseBarDate($last_eco_com_beneficiary->due_date) : $last_eco_com_beneficiary->due_date;
            $eco_com_beneficiary->is_duedate_undefined = $last_eco_com_beneficiary->is_duedate_undefined;
            $eco_com_beneficiary->save();
            /**
             ** has affiliate observation
            */
            $observations = $affiliate->observations()->where('type', 'AT')->whereNull('deleted_at')->get();
            foreach ($observations as $observation) {
                $enabled = false;
                if($observation->id == 31)
                    $enabled = true;
                $economic_complement->observations()->save($observation, [
                    'user_id' => $observation->pivot->user_id,
                    'date' => $observation->pivot->date,
                    'message' => $observation->pivot->message,
                    'enabled' => $enabled
                ]);
            }
            /**
             ** observacion mayor de 25 en orfandad
            */
            if ($economic_complement->eco_com_modality_id == ID::ecoCom()->orphanhood && $eco_com_beneficiary->birth_date) {
                $beneficiary_years = intval(explode(' ', Util::calculateAge($eco_com_beneficiary->birth_date, null)[0]));
                if ($beneficiary_years > 25) {
                    $economic_complement->observations()->save(ObservationType::find(36), [
                        'user_id' => auth()->id(),
                        'date' => now(),
                        'message' => 'Excluido - Huerfano(a) cumplio 25 años. (Observación adicionada automáticamente)',
                        'enabled' => false
                    ]);
                }
            }    
            /**
             ** Update or create address
            */
            if ($last_eco_com_beneficiary->address()->first()) {
                $eco_com_beneficiary->address()->attach($last_eco_com_beneficiary->address()->first()->id);
            }
            /**
             ** save documents
            */
          /*  switch ($economic_complement->eco_com_modality_id) {
                case 1:
                    $requirements_habitual = 1235;
                    break;
                case 2:
                    $requirements_habitual = 1266;
                    break;
                case 3:
                    $requirements_habitual = 1300;
                    break;
                default:
                    # code...
                    break;
            }
            $eco_com_submitted_document= new EcoComSubmittedDocument();
            $eco_com_submitted_document->economic_complement_id = $economic_complement->id;
            $eco_com_submitted_document->procedure_requirement_id = $requirements_habitual;
            $eco_com_submitted_document->reception_date = now();
            $eco_com_submitted_document->save();*/

            Storage::makeDirectory('eco_com/'.$request->affiliate->id, 0775, true);
            Storage::makeDirectory('ci/'.$request->affiliate->id, 0775, true);

            foreach ($request->attachments as $attachment) {
                if (strpos($attachment['filename'], 'ci') !== false) {
                    if (strpos($attachment['filename'], 'ci_anverso') !== false) {
                        $path = 'ci/'.$request->affiliate->id.'/ci_anverso.jpg';
                        Storage::put($path, base64_decode($attachment['content']), 'public');
                    }else {
                        $path = 'ci/'.$request->affiliate->id.'/ci_reverso.jpg';
                        Storage::put($path, base64_decode($attachment['content']), 'public');
                    }
                }
                // else {
                //     $path = 'eco_com/'.$request->affiliate->id.'/boleta_de_renta_'.$eco_com_procedure_id.'.jpg';
                //     Storage::put($path, base64_decode($attachment['content']), 'public');
                // }
            }   

            $economic_complement->procedure_records()->create([
                'user_id' => 171,
                'record_type_id' => 7,
                'wf_state_id' =>  $verified? 1 : 60,
                'date' => Carbon::now(),
                'message' => 'Se creó el trámite mediante aplicación móvil.'
            ]);
            /*creación de doble percepción*/
            if (Util::isDoblePerceptionEcoCom($last_eco_com_beneficiary->identity_card)) {
                $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')->whereIdentityCard($last_eco_com_beneficiary->identity_card)->whereIn('eco_com_modality_id',[2,5,9,7])->first();
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate;
                $last_eco_com = $affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) { $q->orderBy('year')->orderBy('normal_start_date'); })->latest()->first();
                $last_eco_com_beneficiary = $last_eco_com->eco_com_beneficiary()->first();
                $has_economic_complement = $affiliate->hasEconomicComplementWithProcedure($request->eco_com_procedure_id);
                if (!$has_economic_complement) {
                    /**
                     ** Create Economic Complement 
                    */
                    $economic_complement = new EconomicComplement();
                    $economic_complement->user_id = 171;
                    $economic_complement->affiliate_id = $affiliate->id;
                    $economic_complement->eco_com_modality_id = EcoComModality::where('procedure_modality_id','=',$last_eco_com->eco_com_modality->procedure_modality_id)->where('name', 'like', '%normal%')->first()->id;
                    $economic_complement->eco_com_state_id = ID::ecoComState()->in_process;
                    $economic_complement->eco_com_procedure_id = $eco_com_procedure_id;
                    $economic_complement->workflow_id = EcoComProcedure::whereDate('lagging_end_date', '>=', $now)->first()->id? ID::workflow()->eco_com_normal : ID::workflow()->eco_com_additional;
                    $economic_complement->wf_current_state_id = $verified? 1 : 60;
                    $economic_complement->city_id = $last_eco_com->city_id;
                    $economic_complement->degree_id = $affiliate->degree->id;
                    $economic_complement->category_id = $affiliate->category->id;
                    $economic_complement->code = Util::getLastCodeEconomicComplement($eco_com_procedure_id);
                    $economic_complement->reception_date = now();
                    $economic_complement->inbox_state = false;
                    $economic_complement->eco_com_reception_type_id = ID::ecoCom()->habitual;
                    $economic_complement->uuid = Uuid::uuid1()->toString();
                    $economic_complement->save();

                    //Desactivar Observers
                    EconomicComplement::FlushEventListeners(); 
                    $this->updateEcoComWithFixedPension($economic_complement->id);    
                    //Activar Observers
                    EconomicComplement::Boot();
                    
                    /**
                     ** Save eco com beneficiary
                    */
                    $eco_com_beneficiary = new EcoComBeneficiary();
                    $eco_com_beneficiary->economic_complement_id = $economic_complement->id;
                    $eco_com_beneficiary->city_identity_card_id = $last_eco_com_beneficiary->city_identity_card_id;
                    $eco_com_beneficiary->identity_card = $last_eco_com_beneficiary->identity_card;
                    $eco_com_beneficiary->last_name = $last_eco_com_beneficiary->last_name;
                    $eco_com_beneficiary->mothers_last_name = $last_eco_com_beneficiary->mothers_last_name;
                    $eco_com_beneficiary->first_name = $last_eco_com_beneficiary->first_name;
                    $eco_com_beneficiary->second_name = $last_eco_com_beneficiary->second_name;
                    $eco_com_beneficiary->surname_husband = $last_eco_com_beneficiary->surname_husband;
                    $eco_com_beneficiary->birth_date = Util::verifyBarDate($last_eco_com_beneficiary->birth_date) ? Util::parseBarDate($last_eco_com_beneficiary->birth_date) : $last_eco_com_beneficiary->birth_date;
                    $eco_com_beneficiary->nua = $last_eco_com_beneficiary->nua;
                    $eco_com_beneficiary->gender = $last_eco_com_beneficiary->gender;
                    $eco_com_beneficiary->civil_status = $last_eco_com_beneficiary->civil_status;
                    $eco_com_beneficiary->phone_number = $last_eco_com_beneficiary->phone_number;
                    $eco_com_beneficiary->cell_phone_number = $cell_phone_number;
                    $eco_com_beneficiary->city_birth_id = $last_eco_com_beneficiary->city_birth_id;
                    $eco_com_beneficiary->due_date = Util::verifyBarDate($last_eco_com_beneficiary->due_date) ? Util::parseBarDate($last_eco_com_beneficiary->due_date) : $last_eco_com_beneficiary->due_date;
                    $eco_com_beneficiary->is_duedate_undefined = $last_eco_com_beneficiary->is_duedate_undefined;
                    $eco_com_beneficiary->save();
                    /**
                     ** has affiliate observation
                    */
                    $observations = $affiliate->observations()->where('type', 'AT')->whereNull('deleted_at')->get();
                    foreach ($observations as $observation) {
                        $enabled = false;
                        if($observation->id == 31)
                            $enabled = true;
                        $economic_complement->observations()->save($observation, [
                            'user_id' => $observation->pivot->user_id,
                            'date' => $observation->pivot->date,
                            'message' => $observation->pivot->message,
                            'enabled' => $enabled
                        ]);
                    }
                    /**
                     ** observacion mayor de 25 en orfandad
                    */
                    if ($economic_complement->eco_com_modality_id == ID::ecoCom()->orphanhood && $eco_com_beneficiary->birth_date) {
                        $beneficiary_years = intval(explode(' ', Util::calculateAge($eco_com_beneficiary->birth_date, null)[0]));
                        if ($beneficiary_years > 25) {
                            $economic_complement->observations()->save(ObservationType::find(36), [
                                'user_id' => auth()->id(),
                                'date' => now(),
                                'message' => 'Excluido - Huerfano(a) cumplio 25 años. (Observación adicionada automáticamente)',
                                'enabled' => false
                            ]);
                        }
                    }
                    /**
                     ** Update or create address
                    */
                    if ($last_eco_com_beneficiary->address()->first()) {
                        $eco_com_beneficiary->address()->attach($last_eco_com_beneficiary->address()->first()->id);
                    }
                    Storage::makeDirectory('eco_com/'.$affiliate->id, 0775, true);
                    Storage::makeDirectory('ci/'.$request->affiliate->id, 0775, true);

                    foreach ($request->attachments as $attachment) {
                        if (strpos($attachment['filename'], 'ci') !== false) {
                            if (strpos($attachment['filename'], 'ci_anverso') !== false) {
                                $path = 'ci/'.$affiliate->id.'/ci_anverso.jpg';
                                Storage::put($path, base64_decode($attachment['content']), 'public');
                            }else {
                                $path = 'ci/'.$affiliate->id.'/ci_reverso.jpg';
                                Storage::put($path, base64_decode($attachment['content']), 'public');
                            }
                        }
                    }

                    $economic_complement->procedure_records()->create([
                        'user_id' => 171,
                        'record_type_id' => 7,
                        'wf_state_id' => $verified ? 1 : 60,
                        'date' => Carbon::now(),
                        'message' => 'Se creó el trámite mediante aplicación móvil.'
                    ]);
                }
            }

            return $this->print_pdf($economic_complement);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Complemento Económico ya fue registrado.',
                'data' => (object)[],
            ], 403);
        }

    }

    private function print_pdf(EconomicComplement $economic_complement)
    {
        $affiliate = $economic_complement->affiliate;
        $eco_com_beneficiary = $economic_complement->eco_com_beneficiary;
        $eco_com_submitted_documents = $economic_complement->submitted_documents;
        $submitted_document_ids = $economic_complement->submitted_documents->pluck('procedure_requirement_id');
        $eco_com_submitted_documents = ProcedureRequirement::whereIn('id', $submitted_document_ids)->get();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO";
        $title = "SOLICITUD DE PAGO DEL BENEFICIO DE COMPLEMENTO ECONÓMICO";
        $size = 820;
        $size_down = 200;
        $text = "La presente solicitud es generada bajo mi consentimiento a través de la Plataforma Virtual de Tramites – PVT, sin necesidad de firma expresa, para efectos de orden legal.";

        $subtitle = $economic_complement->eco_com_procedure->getTextName() . " - " . mb_strtoupper(optional(optional($economic_complement->eco_com_modality)->procedure_modality)->name);

        $code = $economic_complement->code;
        // $area = $economic_complement->wf_state->first_shortened;
        // $user = $economic_complement->user;
        $area = $economic_complement->wf_records->sortBy('id')->first()->wf_state->first_shortened;
        $user = $economic_complement->wf_records->sortBy('id')->first()->user;
        $date = Util::getDateFormat($economic_complement->reception_date);
        $number = $code;
        if($economic_complement->eco_com_modality->procedure_modality->name != 'Vejez')
            $size = 780;
        if ($economic_complement->eco_com_legal_guardian != null){
            $size = 700;
            $size_down = 80;
        }
        $bar_code = \DNS2D::getBarcodePNG($economic_complement->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user' => $user])->render();

        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'subtitle' => $subtitle,
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,
            'eco_com' => $economic_complement,
            'affiliate' => $affiliate,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'eco_com_submitted_documents' => $eco_com_submitted_documents,
            'text' => $text,
            'habitual' => true,
            'size' => $size,
            'size_down'=> $size_down,
        ];
        $pages = [];

        $pages[] = \View::make('eco_com.print.reception', $data)->render();

        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->setOption('encoding', 'utf-8')
        ->setOption('margin-bottom', '23mm')
        ->setOption('footer-html', $footerHtml)
        ->stream($economic_complement->id . '.pdf');

        return response()->make($pdf->getOutputFromHtml($pages), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="file.pdf"'
        ]);
    }
    //funcion para agregar uuid a los registros que tienen null
    public static function add_uuid(){//aqui
        $eco_coms=EconomicComplement::withTrashed()->get();
        foreach ($eco_coms as $eco_com) {
            $eco_com->uuid=Uuid::uuid1()->toString();
            $eco_com->save();
       }
       return $eco_com;
    }
    public function updateEcoComWithFixedPension($economic_complement_id)
    {
        $economic_complement = EconomicComplement::where('id',$economic_complement_id)->first();
        if(!!$economic_complement){
            if(!($economic_complement->eco_com_reception_type_id == ID::ecoCom()->inclusion)){
                $fixed_pension = EcoComFixedPension::where('affiliate_id', $economic_complement->affiliate_id)
                ->orderBy('created_at','desc')
                ->first();
                if(!!$fixed_pension){  
                    $economic_complement->eco_com_fixed_pension_id = $fixed_pension->id; 
                    $economic_complement->aps_total_fsa = $fixed_pension->aps_total_fsa;    //APS          
                    $economic_complement->aps_total_cc = $fixed_pension->aps_total_cc;      //APS
                    $economic_complement->aps_total_fs = $fixed_pension->aps_total_fs;      //APS
                    $economic_complement->aps_total_death = $fixed_pension->aps_total_death;//APS
                    $economic_complement->aps_disability = $fixed_pension->aps_disability;  //APS //SENASIR

                    $economic_complement->sub_total_rent = $fixed_pension->sub_total_rent;  //SENASIR
                    $economic_complement->reimbursement = $fixed_pension->reimbursement;    //SENASIR
                    $economic_complement->dignity_pension = $fixed_pension->dignity_pension;//SENASIR
                    $economic_complement->total_rent = $fixed_pension->total_rent;          //SENASIR total_rent=sub_total_rent-descuentos planilla

                    $economic_complement->rent_type = 'Automatico';
                    $economic_complement->save();             
                }
            }
        }
    }

    // Por migrar a microservicio
    private function checkObservations($affiliate)
    {
        $observation = $affiliate->observations()->where('enabled', false)->whereNull('deleted_at')->whereIn('id', ObservationType::where('description', 'like', 'Denegado')->where('type', 'like', 'A')->get()->pluck('id'))->get();
        return $observation;
    }
    public function checkAvailability(Request $request)
    {
        $affiliates = [];
        $affiliate = Affiliate::where('identity_card', $request->ci)->first();
        $id = null;
        if ($affiliate) {
            if ($affiliate->affiliate_state->id == 1){
                return response()->json([
                    'error' => true,
                    'canCreate' => false,
                    'message' => 'El afiliado esta activo',
                    'data' => (object)[]
                ], 403);
            }
            // Verifica si el afiliado esta fallecido
            if($affiliate->date_death != null) {
                return response()->json([
                    'error' => false,
                    'canCreate' => false,
                    'message' => 'El afiliado está fallecido',
                    'data' => (object)[]
                ], 403);
            }
            $id = $affiliate->id;
            // Verifica si el afiliado tiene observaciones 
            $observations = $this->checkObservations($affiliate);
            // Verifica si el afiliado tiene tramites de vejez anteriores o si es inclusion
            $isInclusion = EconomicComplement::where('affiliate_id', $affiliate->id)
            ->whereIn('eco_com_modality_id', [1, 4, 6, 8]) // Vejez
            ->count() == 0;
            if($isInclusion) {
                return response()->json([
                    'error' => false,
                    'canCreate' => false,
                    'message' => 'Para casos de inclusión apersonarse a ventanilla de Complemento Económico',
                    'data' => (object)[]
                ], 403);
            }
            if($observations->count() > 0)
            {
                return response()->json([
                    'error' => false,
                    'canCreate' => false,
                    'message' => 'No puede solicitar el Complemento Económico por observaciones',
                    'data' => (object)[]
                ], 403);
            }
            // Verifica si el afiliado es rehabilitación
            $last_eco_com = $affiliate->economic_complements()->orderByDesc('id')->get()->first();
            if ($last_eco_com->eco_com_reception_type_id != 2 && $affiliate->stop_eco_com_consecutively()) {
                return response()->json([
                    'error' => false,
                    'canCreate' => false,
                    'message' => 'Para casos de rehabilitación apersonarse a ventanilla de Complemento Económico',
                    'data' => (object)[]
                ], 403);
            }
            $affiliates[] = $affiliate;
            if (Util::isDoblePerceptionEcoCom($affiliate->identity_card)) {
                $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')->whereIdentityCard($affiliate->identity_card)->whereIn('eco_com_modality_id', [2, 5, 9, 7])->first();
                $affiliates[] = $eco_com_beneficiary->economic_complement->affiliate;
            }
        } else {
            $spouse = Spouse::where('identity_card', $request->ci)->first();
            if ($spouse) {
                if($spouse->date_death != null) {
                    return response()->json([
                        'error' => false,
                        'canCreate' => false,
                        'message' => 'El beneficiario/a está fallecido/a',
                        'data' => (object)[]
                    ], 403);
                }
                $affiliate2 = Affiliate::find($spouse->affiliate_id);
                if ($affiliate2) {
                    $observations = $this->checkObservations($affiliate2);
                    $isInclusion = EconomicComplement::where('affiliate_id', $affiliate2->id)
                    ->whereIn('eco_com_modality_id', [2, 5, 7, 9]) // Viudedad
                    ->count() == 0;
                    if($isInclusion) {
                        return response()->json([
                            'error' => false,
                            'canCreate' => false,
                            'message' => 'Para casos de inclusión apersonarse a ventanilla de Complemento Económico',
                            'data' => (object)[]
                        ], 403);
                    }
                    if($observations->count() > 0)
                    {
                        return response()->json([
                            'error' => false,
                            'canCreate' => false,
                            'message' => 'No puede solicitar el Complemento Económico por observaciones',
                            'data' => (object)[]
                        ], 403);
                    }
                    if ($affiliate2->stop_eco_com_consecutively()) {
                        return response()->json([
                            'error' => false,
                            'canCreate' => false,
                            'message' => 'Para casos de rehabilitación apersonarse a ventanilla de Complemento Económico',
                            'data' => (object)[]
                        ], 403);
                    }
                    $affiliates[] = $affiliate2;
                }
            }
        }
        if (count($affiliates) == 0) {
            return response()->json([
                'error' => true,
                'canCreate' => false,
                'message' => 'No existe afiliado',
                'data' => (object)[]
            ], 404);
        }
        $current_procedures = EcoComProcedure::current_procedures()->pluck('id');
        $available_procedures = EcoComProcedure::whereIn('id', $current_procedures->values())->orderBy('year')->orderBy('normal_start_date')->get();

        if (($available_procedures->count() > 0)) {
            $complements = [];
            $canCreate = false;

            foreach ($affiliates as $affiliate) {
                foreach ($available_procedures as $procedures) {
                    $month = $procedures->rent_month ?? '';
                    $eco_com = EconomicComplement::where('eco_com_procedure_id', $procedures->id)->where('affiliate_id', $affiliate->id)->first();
                    $complements[] = [
                        'procedure_id' => $procedures->id,
                        'month' => $month != '' ? $month . '/' . strval(Carbon::parse($procedures->year)->year) : '',
                        'eco_com_id' => !!$eco_com ? $eco_com->id : null,
                        'affiliate_id' => $affiliate->id
                    ];
                    if (!$eco_com) {
                        $canCreate = true;
                    }
                }
            }

            return response()->json([
                'error' => false,
                'canCreate' => true,
                'available_procedures' => $available_procedures->pluck('id'),
                'affiliate_id' => $id ?? $affiliates[0]->id,
                'message' => '',
                'data' => $complements,
            ]);
        }
        return response()->json([
            'error' => false,
            'canCreate' => false,
            'message' => 'No es posible crear trámites el periodo de creación de trámites no esta habilitado',
            'data' => [],
        ], 400);
    }

    public static function showFormatedEcoCom(Request $request)
    {
        $eco_com = EconomicComplement::find($request->eco_com_id);
        if(!!$eco_com){
            return response()->json([
                'error' => false,
                'message' => 'Puede crear trámites',
                'data' => new EconomicComplementResource($eco_com),
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'No existe el complemento',
                'data' => [],
            ], 400);
        }
    }

    public function createEcoCom(Request $request)
    {
        $eco_com_procedure_id = $request->eco_com_procedure_id;
        
        $user = User::where('username', 'Kiosco Digital')->value('id');
        $user_id = $user;
        $wf_state = WorkflowState::where('name', 'Kiosco Digital')->first();
        $wf_current_state_id = $wf_state->id;
        $affiliate = Affiliate::find($request->affiliate_id);
        $eco_com_id = [];
        
        $last_eco_com = $affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) { $q->orderBy('year')->orderBy('normal_start_date'); })->latest()->first();
        $last_eco_com_beneficiary = $last_eco_com->eco_com_beneficiary()->first();        
        $has_economic_complement = $affiliate->hasEconomicComplementWithProcedure($request->eco_com_procedure_id);
        if (!$has_economic_complement) {
            /**
             ** Create Economic Complement 
            */
            $economic_complement = $this->createEconomicComplement($user_id, $affiliate, $wf_current_state_id, $last_eco_com, $eco_com_procedure_id);
            $eco_com_id[] = $economic_complement->id;
            
            /**
             ** Save eco com beneficiary
            */
            $eco_com_beneficiary = $this->replicateBeneficiary($economic_complement, $last_eco_com_beneficiary);
            
            $this->addObservations($economic_complement, $affiliate, $eco_com_beneficiary);
            
            $economic_complement->procedure_records()->create([
                'user_id' => $user_id,
                'record_type_id' => 7,
                'wf_state_id' =>  $wf_current_state_id,
                'date' => Carbon::now(),
                'message' => 'Se creó el trámite mediante kiosko.'
            ]);
            /*creación de doble percepción*/
            if (Util::isDoblePerceptionEcoCom($last_eco_com_beneficiary->identity_card)) {
                $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')->whereIdentityCard($last_eco_com_beneficiary->identity_card)->whereIn('eco_com_modality_id',[2,5,9,7])->first();
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate;
                
                $last_eco_com = $affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) { $q->orderBy('year')->orderBy('normal_start_date'); })->latest()->first();
                $last_eco_com_beneficiary = $last_eco_com->eco_com_beneficiary()->first();
                $has_economic_complement = $affiliate->hasEconomicComplementWithProcedure($request->eco_com_procedure_id);
                if (!$has_economic_complement) {
                    /**
                     ** Create Economic Complement 
                    */
                    $economic_complement = $this->createEconomicComplement($user_id, $affiliate, $wf_current_state_id, $last_eco_com, $eco_com_procedure_id);
                    $eco_com_id[] = $economic_complement->id;
                    /**
                     ** Save eco com beneficiary
                    */
                    $eco_com_beneficiary = $this->replicateBeneficiary($economic_complement, $last_eco_com_beneficiary);
                    /**
                     ** has affiliate observation
                    */
                    $this->addObservations($economic_complement, $affiliate, $eco_com_beneficiary);

                    $economic_complement->procedure_records()->create([
                        'user_id' => $user_id,
                        'record_type_id' => 7,
                        'wf_state_id' => $wf_current_state_id,
                        'date' => Carbon::now(),
                        'message' => 'Se creó el trámite mediante kiosko.'
                    ]);
                }
            }

            return response()->json([
                'error' => false,
                'message' => 'Complemento Económico creado.',
                'eco_com_id' => $eco_com_id
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Complemento Económico ya fue registrado.',
                'data' => (object)[],
            ], 403);
        }
    }

    private function createEconomicComplement($user_id, $affiliate, $wf_current_state_id, $last_eco_com, $eco_com_procedure_id) {
        $now = Carbon::now()->toDateString();
        $eco_com_modality = EcoComModality::where('procedure_modality_id', '=', $last_eco_com->eco_com_modality->procedure_modality_id)->where('name', 'like', '%normal%')->first()->id;
        $workflow_id = EcoComProcedure::whereDate('lagging_end_date', '>=', $now)->first()->id? ID::workflow()->eco_com_normal : ID::workflow()->eco_com_additional;
        $economic_complement = new EconomicComplement([
            'user_id' => $user_id,
            'affiliate_id' => $affiliate->id,
            'eco_com_modality_id' => $eco_com_modality,
            'eco_com_state_id' => ID::ecoComState()->in_process,
            'eco_com_procedure_id' => $eco_com_procedure_id,
            'workflow_id' => $workflow_id,
            'wf_current_state_id' => $wf_current_state_id,
            'city_id' => $last_eco_com->city_id,
            'degree_id' => $affiliate->degree->id,
            'category_id' => $affiliate->category->id,
            'code' => Util::getLastCodeEconomicComplement($eco_com_procedure_id),
            'reception_date' => now(),
            'inbox_state' => false,
            'eco_com_reception_type_id' => ID::ecoCom()->habitual,
            'uuid' => Uuid::uuid1()->toString(),
        ]);
        $economic_complement->save();

        //Desactivar Observers
        EconomicComplement::FlushEventListeners(); 
        $this->updateEcoComWithFixedPension($economic_complement->id);    
        //Activar Observers
        EconomicComplement::Boot();

        return $economic_complement;
    }

    private function replicateBeneficiary($eco_com, $last_eco_com_beneficiary)
    {
        $eco_com_beneficiary = new EcoComBeneficiary();
        $eco_com_beneficiary->economic_complement_id = $eco_com->id;
        $eco_com_beneficiary->city_identity_card_id = $last_eco_com_beneficiary->city_identity_card_id;
        $eco_com_beneficiary->identity_card = $last_eco_com_beneficiary->identity_card;
        $eco_com_beneficiary->last_name = $last_eco_com_beneficiary->last_name;
        $eco_com_beneficiary->mothers_last_name = $last_eco_com_beneficiary->mothers_last_name;
        $eco_com_beneficiary->first_name = $last_eco_com_beneficiary->first_name;
        $eco_com_beneficiary->second_name = $last_eco_com_beneficiary->second_name;
        $eco_com_beneficiary->surname_husband = $last_eco_com_beneficiary->surname_husband;
        $eco_com_beneficiary->birth_date = Util::verifyBarDate($last_eco_com_beneficiary->birth_date) ? Util::parseBarDate($last_eco_com_beneficiary->birth_date) : $last_eco_com_beneficiary->birth_date;
        $eco_com_beneficiary->nua = $last_eco_com_beneficiary->nua;
        $eco_com_beneficiary->gender = $last_eco_com_beneficiary->gender;
        $eco_com_beneficiary->civil_status = $last_eco_com_beneficiary->civil_status;
        $eco_com_beneficiary->phone_number = $last_eco_com_beneficiary->phone_number;
        //$eco_com_beneficiary->cell_phone_number = $cell_phone_number;
        $eco_com_beneficiary->city_birth_id = $last_eco_com_beneficiary->city_birth_id;
        $eco_com_beneficiary->due_date = Util::verifyBarDate($last_eco_com_beneficiary->due_date) ? Util::parseBarDate($last_eco_com_beneficiary->due_date) : $last_eco_com_beneficiary->due_date;
        $eco_com_beneficiary->is_duedate_undefined = $last_eco_com_beneficiary->is_duedate_undefined;
        $eco_com_beneficiary->save();

        if ($last_eco_com_beneficiary->address()->first()) {
            $eco_com_beneficiary->address()->attach($last_eco_com_beneficiary->address()->first()->id);
        } 

        return $eco_com_beneficiary;
    }

    private function addObservations($economic_complement, $affiliate, $eco_com_beneficiary){
        /**
         ** has affiliate observation
         */
        $observations = $affiliate->observations()->where('type', 'AT')->whereNull('deleted_at')->get();
        foreach ($observations as $observation) {
            $enabled = false;
            if ($observation->id == 31) // Descuento - Aporte para el Auxilio Mortuorio mediante el Complemento Económico.
                $enabled = true;
            $economic_complement->observations()->save($observation, [
                'user_id' => $observation->pivot->user_id,
                'date' => $observation->pivot->date,
                'message' => $observation->pivot->message,
                'enabled' => $enabled
            ]);
        }
        /**
         ** observacion mayor de 25 en orfandad
         */
        if ($economic_complement->eco_com_modality_id == ID::ecoCom()->orphanhood && $eco_com_beneficiary->birth_date) {
            $beneficiary_years = intval(explode(' ', Util::calculateAge($eco_com_beneficiary->birth_date, null)[0]));
            if ($beneficiary_years > 25) {
                $economic_complement->observations()->save(ObservationType::find(36), [
                    'user_id' => auth()->id(),
                    'date' => now(),
                    'message' => 'Excluido - Huerfano(a) cumplio 25 años. (Observación adicionada automáticamente)',
                    'enabled' => false
                ]);
            }
        }    
    }
    
}
