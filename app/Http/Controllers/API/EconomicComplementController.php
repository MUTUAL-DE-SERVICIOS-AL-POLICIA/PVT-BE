<?php

namespace Muserpol\Http\Controllers\API;

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
use Muserpol\Helpers\Util;
use Muserpol\Helpers\ID;
use Carbon\Carbon;

class EconomicComplementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->affiliate->economic_complements()->has('eco_com_beneficiary')->orderBy('reception_date', 'desc');
        $current_procedures = EcoComProcedure::current_procedures()->pluck('id');
        if (filter_var($request->query('current'), FILTER_VALIDATE_BOOLEAN, false)) {
            $state_types = EcoComStateType::whereIn('name', ['Enviado', 'Creado'])->pluck('id');
            $data = $data->where(function($q) use ($current_procedures, $state_types) {
                $q->whereIn('eco_com_procedure_id', $current_procedures)->orWhereHas('eco_com_state', function ($query) use ($state_types) {
                    return $query->whereIn('eco_com_state_type_id', $state_types);
                });
            });
        } else {
            $state_types = EcoComStateType::whereIn('name', ['Pagado', 'No Efectivizado'])->pluck('id');
            $data = $data->where(function($q) use ($current_procedures, $state_types) {
                $q->whereNotIn('eco_com_procedure_id', $current_procedures)->whereHas('eco_com_state', function ($query) use ($state_types) {
                    return $query->whereIn('eco_com_state_type_id', $state_types);
                });
            });
        }
        return response()->json([
            'error' => false,
            'message' => 'Complemento Económico',
            'data' => EconomicComplementResource::collection($data->paginate($request->per_page ?? 3, ['*'], 'page', $request->page ?? 1))->resource,
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
        if ($economicComplement->affiliate_id == $request->affiliate->id) {
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $eco_com_procedure_id = $request->eco_com_procedure_id;
        $affiliate = $request->affiliate;
        $last_eco_com = $request->affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) { $q->orderBy('year')->orderBy('normal_start_date'); })->latest()->first();
        $last_eco_com_beneficiary = $last_eco_com->eco_com_beneficiary()->first();        
        $now = Carbon::now()->toDateString();

        $has_economic_complement = $affiliate->hasEconomicComplementWithProcedure($request->eco_com_procedure_id);
        if (!$has_economic_complement) {
            /**
             ** Create Economic Complement 
            */
            $economic_complement = new EconomicComplement();
            $economic_complement->user_id = 1;
            $economic_complement->affiliate_id = $affiliate->id;
            $economic_complement->eco_com_modality_id = EcoComModality::where('procedure_modality_id','=',$last_eco_com->eco_com_modality->procedure_modality_id)->where('name', 'like', '%normal%')->first()->id;
            $economic_complement->eco_com_state_id = ID::ecoComState()->in_process;
            $economic_complement->eco_com_procedure_id = $eco_com_procedure_id;
            $economic_complement->workflow_id = EcoComProcedure::whereDate('normal_end_date', '>=', $now)->first()->id? ID::workflow()->eco_com_normal : ID::workflow()->eco_com_additional;
            $economic_complement->wf_current_state_id = 1;
            $economic_complement->city_id = ID::cityId()->LP;
            $economic_complement->degree_id = $affiliate->degree->id;
            $economic_complement->category_id = $affiliate->category->id;
            $economic_complement->code = Util::getLastCodeEconomicComplement($eco_com_procedure_id);
            $economic_complement->reception_date = now();
            $economic_complement->inbox_state = false;
            $economic_complement->eco_com_reception_type_id = ID::ecoCom()->habitual;

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
                $economic_complement->reimbursement = null;
                $economic_complement->dignity_pension = null;
                $economic_complement->total_rent =
                $economic_complement->aps_total_fsa +
                $economic_complement->aps_total_cc +
                $economic_complement->aps_total_fs +
                $economic_complement->aps_disability;
            }
            $economic_complement->save();
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
            $eco_com_beneficiary->birth_date = Util::parseBarDate($last_eco_com_beneficiary->birth_date);
            $eco_com_beneficiary->nua = $last_eco_com_beneficiary->nua;
            $eco_com_beneficiary->gender = $last_eco_com_beneficiary->gender;
            $eco_com_beneficiary->civil_status = $last_eco_com_beneficiary->civil_status;
            $eco_com_beneficiary->phone_number = $last_eco_com_beneficiary->phone_number;
            $eco_com_beneficiary->cell_phone_number = $last_eco_com_beneficiary->cell_phone_number;
            $eco_com_beneficiary->city_birth_id = $last_eco_com_beneficiary->city_birth_id;
            $eco_com_beneficiary->due_date = $last_eco_com_beneficiary->due_date;
            $eco_com_beneficiary->is_duedate_undefined = $last_eco_com_beneficiary->is_duedate_undefined;
            $eco_com_beneficiary->save();
            /**
             ** has affiliate observation
            */
            $observations = $affiliate->observations()->where('type', 'AT')->whereNull('deleted_at')->get();
            foreach ($observations as $observation) {
                $economic_complement->observations()->save($observation, [
                    'user_id' => $observation->pivot->user_id,
                    'date' => $observation->pivot->date,
                    'message' => $observation->pivot->message,
                    'enabled' => false
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
            switch ($economic_complement->eco_com_modality_id) {
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
            $eco_com_submitted_document->save();


            $submitted_document_ids = $economic_complement->submitted_documents->pluck('procedure_requirement_id');
            $eco_com_submitted_documents = ProcedureRequirement::whereIn('id', $submitted_document_ids)->get();
            $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
            $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
            $unit = "UNIDAD DE OTORGACIÓN DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO";
            if($economic_complement->eco_com_reception_type_id == ID::ecoCom()->habitual){
                $title = "FORMULARIO DE REGISTRO DE PAGO DEL BENEFICIO DE COMPLEMENTO ECONÓMICO";
            }else{
                $title = "SOLICITUD DE PAGO DEL BENEFICIO DE COMPLEMENTO ECONÓMICO";
            }
            $subtitle = $economic_complement->eco_com_procedure->getTextName() . " " . mb_strtoupper(optional(optional($economic_complement->eco_com_modality)->procedure_modality)->name);
    
            $code = $economic_complement->code;
            $area = $economic_complement->wf_state->first_shortened;
            $user = $economic_complement->user;
            $date = Util::getDateFormat($economic_complement->reception_date);
            $number = $code;
    
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
            ];
            $pages = [];

            $pages[] = \View::make('eco_com.print.reception', $data)->render();

            $pdf = \App::make('snappy.pdf');
            $pdf->getOutputFromHtml($pages);

            return response()->make($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$pdf.'"'
            ]);

        } else {
            return response()->json([
                'error' => true,
                'message' => 'Complemento Económico ya fue registrado.',
                'data' => (object)[],
            ], 403);
        }
        
    }
}
