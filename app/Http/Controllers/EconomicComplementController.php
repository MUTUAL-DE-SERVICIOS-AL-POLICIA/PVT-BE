<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Illuminate\Support\Facades\Auth;
use Muserpol\Helpers\Util;
use Muserpol\Models\City;
use Muserpol\Models\Spouse;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\EconomicComplement\EcoComLegalGuardian;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Address;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\ProcedureState;
use Muserpol\Models\PensionEntity;
use Muserpol\User;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\Degree;
use Muserpol\Models\Category;
use Log;
use Muserpol\Models\AffiliateState;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Models\EconomicComplement\EcoComType;
use Carbon\Carbon;
use DB;
use Muserpol\Models\EconomicComplement\EcoComSubmittedDocument;
use Muserpol\Models\Role;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\EconomicComplement\EconomicComplementRecord;
use Muserpol\Models\EconomicComplement\EcoComRent;
use Muserpol\Models\ObservationType;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Muserpol\Models\EconomicComplement\EcoComState;
use Illuminate\Validation\ValidationException;
use Muserpol\Models\DiscountType;
use Muserpol\Models\ComplementaryFactor;

class EconomicComplementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $modalities =  ProcedureModality::all()->pluck('name');
        // $cities =  City::all()->pluck('name');
        // $wf_states =  WorkflowState::where('module_id', 3)->get()->pluck('first_shortened');
        $data = [
            // 'modalities' => $modalities,
            // 'cities' => $cities,
            // 'wf_states' => $wf_states,
        ];
        return view('eco_com.index', $data);
    }
    public function getAllEcoCom(DataTables $datatables)
    {
        $eco_coms = EconomicComplement::with([
            'affiliate:id,identity_card,city_identity_card_id,first_name,second_name,last_name,mothers_last_name,surname_husband,gender,degree_id,degree_id,pension_entity_id',
            'city:id,name,first_shortened',
            'wf_state:id,name,first_shortened',
            'eco_com_modality:id,name,shortened',
            'eco_com_beneficiary',
            'workflow:id,name',
        ])->select(
            'id',
            'code',
            'reception_date',
            'total',
            'affiliate_id',
            'city_id',
            'state',
            'total',
            'wf_current_state_id',
            'eco_com_modality_id',
            'eco_com_procedure_id',
            'workflow_id'
        )
            ->where('code', 'not like', '%A')
            ->orderByDesc(DB::raw("split_part(code, '/',3)::integer desc, split_part(code, '/',2), split_part(code, '/',1)::integer"));
        return $datatables->eloquent($eco_coms)
            ->addColumn('eco_com_beneficiary_ci_with_ext', function ($eco_com) {
                return $eco_com->eco_com_beneficiary ? $eco_com->eco_com_beneficiary->ciWithExt() : '';
            })
            ->addColumn('eco_com_beneficiary_full_name', function ($eco_com) {
                return $eco_com->eco_com_beneficiary ? $eco_com->eco_com_beneficiary->fullName() : '';
            })
            ->addColumn('procedure', function ($eco_com) {
                return $eco_com->eco_com_procedure ? $eco_com->eco_com_procedure->fullName() : '';
            })
            ->editColumn('state', function ($eco_com) {
                return $eco_com->inbox_state ? 'Validado' : 'Pendiente';
            })
            ->addColumn('pension_entity', function ($eco_com) {
                return $eco_com->affiliate->pension_entity->name ?? null;
            })
            ->addColumn('action', function ($eco_com) {
                return "<a href='/eco_com/" . $eco_com->id . "' class='btn btn-default'><i class='fa fa-eye'></i></a>";
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($affiliate_id, $eco_com_procedure_id)
    {
        $affiliate = Affiliate::with(['pension_entity'])->find($affiliate_id);
        $has_economic_complement = $affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_id);
        if ($has_economic_complement) {
            return redirect()->action('EconomicComplementController@show', ['id' => $affiliate->economic_complements()->where('eco_com_procedure_id', $eco_com_procedure_id)->first()->id]);
        }
        if ($affiliate->observations()->where('enabled',false)->whereIn('id', ObservationType::where('type', 'A')->get()->pluck('id'))->get()->count()){
            return redirect()->action('AffiliateController@show', ['id' => $affiliate->id]);
        }
        $cities = City::all();
        $eco_com_beneficiary = new EcoComBeneficiary();
        $eco_com_beneficiary->phone_number = explode(',', $eco_com_beneficiary->phone_number);
        $eco_com_beneficiary->cell_phone_number = explode(',', $eco_com_beneficiary->cell_phone_number);
        if (!sizeOf($eco_com_beneficiary->address) > 0) {
            $eco_com_beneficiary->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }
        $requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
            ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
            ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
            ->orderBy('procedure_requirements.number', 'ASC')
            ->get();
        $user = Auth::user();
        $last_eco_com = $affiliate->economic_complements()->orderByDesc('id')->get()->first();
        if ($last_eco_com) {
            $last_eco_com->procedure_modality_id = $last_eco_com->eco_com_modality->eco_com_type_id;
        } else {
            $last_eco_com = new EconomicComplement();
        }
        $modalities = EcoComType::all();
        $pension_entities = PensionEntity::all();
        $data = [
            'affiliate' => $affiliate,
            'cities' => $cities,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'requirements' => $requirements,
            'user' => $user,
            'last_eco_com' => $last_eco_com,
            'eco_com_procedure_id' => $eco_com_procedure_id,
            'modalities' => $modalities,
            'pension_entities' => $pension_entities,
        ];

        return view('eco_com.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $this->authorize('create', new EconomicComplement());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para crear el tramite'],
            ], 403);
        }
        $eco_com_procedure = EcoComProcedure::find($request->eco_com_procedure_id);
        if (!$eco_com_procedure) {
            abort(500, "ERROR");
        }
        $affiliate = Affiliate::find($request->affiliate_id);
        $has_economic_complement = $affiliate->hasEconomicComplementWithProcedure($request->eco_com_procedure_id);
        if ($has_economic_complement) {
            return redirect()->action('EconomicComplementController@show', ['id' => $affiliate->economic_complements()->where('eco_com_procedure_id', $request->eco_com_procedure_id)->first()->id]);
        }
        /**
         ** update affiliate info
         */
        $affiliate->pension_entity_id = $request->pension_entity_id;
        $affiliate->save();
        /**
         ** create Economic complement 
         */
        $economic_complement = new EconomicComplement();
        $economic_complement->user_id = Auth::user()->id;
        $economic_complement->affiliate_id = $affiliate->id;
        $economic_complement->eco_com_modality_id = $request->modality_id;
        $economic_complement->eco_com_state_id = 16;
        $economic_complement->eco_com_procedure_id = $request->eco_com_procedure_id;
        $economic_complement->workflow_id = 1;
        /**
         * !! TODO regionales
         */
        $economic_complement->wf_current_state_id = 1;

        $economic_complement->city_id = $request->city_id;
        $economic_complement->degree_id = $affiliate->degree->id;
        $economic_complement->category_id = $affiliate->category->id;
        // $economic_complement->base_wage_id = 2;
        // $economic_complement->complementary_factor_id = 2;
        $economic_complement->year = Carbon::parse($eco_com_procedure->year)->year . '-01-01';
        $economic_complement->semester = $eco_com_procedure->semester;
        $economic_complement->has_legal_guardian = $request->has_legal_guardian == 'on'; // solicitante y cobrador
        $economic_complement->has_legal_guardian_s = $request->legal_guardian_type_id == 1; // solo solicitante
        $economic_complement->code = Util::getLastCodeEconomicComplement($request->eco_com_procedure_id);
        $economic_complement->reception_date = now();
        /**
         *!!TODO change inbox_state column
         **/
        $economic_complement->inbox_state = true;
        $economic_complement->state = 'Received';
        $economic_complement->reception_type = $request->reception_type == 2 ? 'Habitual' : 'Inclusion';

        if ($request->pension_entity_id == 5) {
            $economic_complement->sub_total_rent = Util::parseMoney($request->sub_total_rent);
            $economic_complement->reimbursement = Util::parseMoney($request->reimbursement);
            $economic_complement->dignity_pension = Util::parseMoney($request->dignity_pension);
            $economic_complement->aps_disability = Util::parseMoney($request->aps_total_disability);
            $economic_complement->aps_total_fsa = null;
            $economic_complement->aps_total_cc = null;
            $economic_complement->aps_total_fs = null;
        } else {
            $economic_complement->aps_total_fsa = Util::parseMoney($request->aps_total_fsa);
            $economic_complement->aps_total_cc = Util::parseMoney($request->aps_total_cc);
            $economic_complement->aps_total_fs = Util::parseMoney($request->aps_total_fs);
            $economic_complement->aps_disability = Util::parseMoney($request->aps_total_disability);
            $economic_complement->sub_total_rent = null;
            $economic_complement->reimbursement = null;
            $economic_complement->dignity_pension = null;
        }
        $economic_complement->save();
        /**
         ** has affiliate observation
         */
        $observations = $affiliate->observations()->where('type', 'AT')->get();
        foreach ($observations as $o) {
            $economic_complement->observations()->save($o, [
                'user_id' => $o->pivot->user_id,
                'date' => $o->pivot->date,
                'message' => $o->pivot->message,
                'enabled' => false
            ]);
            // $record = new EconomicComplementRecord();
            // $record->user_id = Auth::user()->id;
            // $record->economic_complement_id = $economic_complement->id;
            // $record->message = "El usuario " . User::find($o->user_id)->username  . " creó la observación " . $o->name . ".";
            // $record->save();
        }
        /**
         ** verify observation
         */
        $number_docs = ProcedureModality::find($request->modality_id)->procedure_requirements->pluck('number')->unique()->sort();
        if($number_docs->contains(0)){
            $number_docs = $number_docs->slice(1);
        }
        $count = 0;
        foreach($request->all() as $key => $value){
            if (strpos($key, 'document') !== false  && $value == 'checked' ) {
                $count++;
            }
        }
        if($count != $number_docs->count()){
            $economic_complement->observations()->save(ObservationType::find(6), [
                'user_id' => auth()->id(),
                'date' => now(),
                'message' => 'Documentación incompleta (Observación adicionada automáticamente)',
                'enabled' => false
            ]);
        }
        /**
         ** Save legal guardian
         */
        if ($request->has_legal_guardian == 'on') {
            $legal_guardian = new EcoComLegalGuardian();
            $legal_guardian->economic_complement_id = $economic_complement->id;
            $legal_guardian->city_identity_card_id = $request->legal_guardian_city_identity_card;
            $legal_guardian->identity_card = $request->legal_guardian_identity_card;
            $legal_guardian->last_name = $request->legal_guardian_last_name;
            $legal_guardian->mothers_last_name = $request->legal_guardian_mothers_last_name;
            $legal_guardian->first_name = $request->legal_guardian_first_name;
            $legal_guardian->second_name = $request->legal_guardian_second_name;
            $legal_guardian->surname_husband = $request->legal_guardian_surname_husband;
            $legal_guardian->phone_number = implode(',', $request->legal_guardian_phone_number ?? []);
            $legal_guardian->cell_phone_number = implode(',', $request->legal_guardian_cell_phone_number ?? []);
            $legal_guardian->due_date = Util::verifyBarDate($request->legal_guardian_due_date) ? Util::parseBarDate($request->legal_guardian_due_date) : $request->legal_guardian_due_date;
            $legal_guardian->is_duedate_undefined = $request->legal_guardian_is_duedate_undefined == 'on';
            if ($request->legal_guardian_is_duedate_undefined == 'on') {
                $legal_guardian->due_date = null;
            }
            $legal_guardian->save();
        }
        /**
         ** Save eco com beneficiary
         */
        $eco_com_beneficiary = new EcoComBeneficiary();
        $eco_com_beneficiary->economic_complement_id = $economic_complement->id;
        $eco_com_beneficiary->city_identity_card_id = $request->eco_com_beneficiary_city_identity_card_id;
        $eco_com_beneficiary->identity_card = $request->eco_com_beneficiary_identity_card;
        $eco_com_beneficiary->last_name = $request->eco_com_beneficiary_last_name;
        $eco_com_beneficiary->mothers_last_name = $request->eco_com_beneficiary_mothers_last_name;
        $eco_com_beneficiary->first_name = $request->eco_com_beneficiary_first_name;
        $eco_com_beneficiary->second_name = $request->eco_com_beneficiary_second_name;
        $eco_com_beneficiary->surname_husband = $request->eco_com_beneficiary_surname_husband ?? null;
        $eco_com_beneficiary->birth_date = Util::verifyBarDate($request->eco_com_beneficiary_birth_date) ? Util::parseBarDate($request->eco_com_beneficiary_birth_date) : $request->eco_com_beneficiary_birth_date;
        $eco_com_beneficiary->nua = $request->eco_com_beneficiary_nua;
        $eco_com_beneficiary->gender = $request->eco_com_beneficiary_gender;
        $eco_com_beneficiary->civil_status = $request->eco_com_beneficiary_civil_status;
        $eco_com_beneficiary->phone_number = trim(implode(",", $request->eco_com_beneficiary_phone_number ?? []));
        $eco_com_beneficiary->cell_phone_number = trim(implode(",", $request->eco_com_beneficiary_cell_phone_number ?? []));
        $eco_com_beneficiary->city_birth_id = $request->eco_com_beneficiary_city_birth_id;
        $eco_com_beneficiary->due_date = Util::verifyBarDate($request->eco_com_beneficiary_due_date) ? Util::parseBarDate($request->eco_com_beneficiary_due_date) : $request->eco_com_beneficiary_due_date;
        $eco_com_beneficiary->is_duedate_undefined = $request->eco_com_beneficiary_is_duedate_undefined == 'on';
        if ($request->eco_com_beneficiary_is_duedate_undefined == 'on') {
            $eco_com_beneficiary->due_date = null;
        }
        $eco_com_beneficiary->save();

        /**
         ** Update or create address
         */
        if (sizeOf($eco_com_beneficiary->address) > 0) {
            $address_id = $eco_com_beneficiary->address()->first()->id;
            $address = Address::find($address_id);
            if ($request->beneficiary_zone || $request->beneficiary_street || $request->beneficiary_number_address) {
                $address->city_address_id = $request->beneficiary_city_address_id ?? 1;
                $address->zone = $request->beneficiary_zone;
                $address->street = $request->beneficiary_street;
                $address->number_address = $request->beneficiary_number_address;
                $address->save();
                if ($economic_complement->procedure_modality_id == 24) {
                    $update_affiliate = Affiliate::find($economic_complement->affiliate_id);
                    if ($update_affiliate->address->contains($address->id)) { } else {
                        $update_affiliate->address()->save($address);
                    }
                }
            } else {
                if ($economic_complement->procedure_modality_id == 24) {
                    $update_affiliate = Affiliate::find($economic_complement->affiliate_id);
                    $update_affiliate->address()->detach($address->id);
                }
                $eco_com_beneficiary->address()->detach($address->id);
                $address->delete();
            }
        } else {
            if ($request->beneficiary_city_address_id) {
                $address = new Address();
                $address->city_address_id = $request->beneficiary_city_address_id ?? 1;
                $address->zone = $request->beneficiary_zone;
                $address->street = $request->beneficiary_street;
                $address->number_address = $request->beneficiary_number_address;
                $address->save();
                $eco_com_beneficiary->address()->save($address);
                if ($economic_complement->procedure_modality_id == 24) {
                    $update_affiliate = Affiliate::find($economic_complement->affiliate_id);
                    $update_affiliate->address()->save($address);
                }
            }
        }
        $eco_com_beneficiary->save();

        /**
         ** update affiliate and spouse
         */
        switch ($request->modality_id) {
                // vejez update affiliate
            case 1:
                $affiliate->city_identity_card_id = $request->eco_com_beneficiary_city_identity_card_id;
                $affiliate->identity_card = $request->eco_com_beneficiary_identity_card;
                $affiliate->last_name = $request->eco_com_beneficiary_last_name;
                $affiliate->mothers_last_name = $request->eco_com_beneficiary_mothers_last_name;
                $affiliate->first_name = $request->eco_com_beneficiary_first_name;
                $affiliate->second_name = $request->eco_com_beneficiary_second_name;
                $affiliate->surname_husband = $request->eco_com_beneficiary_surname_husband ?? null;
                $affiliate->birth_date = Util::verifyBarDate($request->eco_com_beneficiary_birth_date) ? Util::parseBarDate($request->eco_com_beneficiary_birth_date) : $request->eco_com_beneficiary_birth_date;
                $affiliate->nua = $request->eco_com_beneficiary_nua;
                $affiliate->gender = $request->eco_com_beneficiary_gender;
                $affiliate->civil_status = $request->eco_com_beneficiary_civil_status;
                $affiliate->phone_number = trim(implode(",", $request->eco_com_beneficiary_phone_number ?? []));
                $affiliate->cell_phone_number = trim(implode(",", $request->eco_com_beneficiary_cell_phone_number ?? []));
                $affiliate->city_birth_id = $request->eco_com_beneficiary_city_birth_id;
                $affiliate->due_date = Util::verifyBarDate($request->eco_com_beneficiary_due_date) ? Util::parseBarDate($request->eco_com_beneficiary_due_date) : $request->eco_com_beneficiary_due_date;
                $affiliate->is_duedate_undefined = $request->eco_com_beneficiary_is_duedate_undefined == 'on';
                if ($request->eco_com_beneficiary_is_duedate_undefined == 'on') {
                    $affiliate->due_date = null;
                }
                $affiliate->save();
                break;
                // viudedad update or create spouse
            case 2:
                $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
                if (!$spouse) {
                    $spouse = new Spouse();
                }
                $spouse->user_id = Auth::user()->id;
                $spouse->affiliate_id = $affiliate->id;
                $spouse->city_identity_card_id = $request->eco_com_beneficiary_city_identity_card_id;
                $spouse->identity_card = $request->eco_com_beneficiary_identity_card;
                $spouse->registration = "";
                $spouse->last_name = $request->eco_com_beneficiary_last_name;
                $spouse->mothers_last_name = $request->eco_com_beneficiary_mothers_last_name;
                $spouse->first_name = $request->eco_com_beneficiary_first_name;
                $spouse->second_name = $request->eco_com_beneficiary_second_name;
                $spouse->surname_husband = $request->eco_com_beneficiary_surname_husband ?? null;
                $spouse->civil_status = $request->eco_com_beneficiary_civil_status;
                $spouse->birth_date = Util::verifyBarDate($request->eco_com_beneficiary_birth_date) ? Util::parseBarDate($request->eco_com_beneficiary_birth_date) : $request->eco_com_beneficiary_birth_date;
                $spouse->city_birth_id = $request->eco_com_beneficiary_city_birth_id;
                // $spouse->gender = $request->eco_com_beneficiary_gender;
                // $spouse-> = trim(implode(",", $request->eco_com_beneficiary_phone_number));
                // $spouse-> = trim(implode(",", $request->eco_com_beneficiary_cell_phone_number));
                $spouse->due_date = Util::verifyBarDate($request->eco_com_beneficiary_due_date) ? Util::parseBarDate($request->eco_com_beneficiary_due_date) : $request->eco_com_beneficiary_due_date;
                $spouse->is_duedate_undefined = $request->eco_com_beneficiary_is_duedate_undefined == 'on';
                if ($request->eco_com_beneficiary_is_duedate_undefined == 'on') {
                    $spouse->due_date = null;
                }
                $spouse->save();

                /**
                 *update affiliate
                 */
                $affiliate->identity_card = $request->affiliate_identity_card;
                $affiliate->city_identity_card_id = $request->affiliate_city_identity_card_id;
                $affiliate->last_name = $request->affiliate_last_name;
                $affiliate->mothers_last_name = $request->affiliate_mothers_last_name;
                $affiliate->first_name = $request->affiliate_first_name;
                $affiliate->second_name = $request->affiliate_second_name;
                $affiliate->surname_husband = $request->affiliate_surname_husband ?? null;
                $affiliate->birth_date = Util::verifyBarDate($request->affiliate_birth_date) ? Util::parseBarDate($request->affiliate_birth_date) : $request->affiliate_birth_date;
                $affiliate->gender = $request->affiliate_gender;
                $affiliate->save();

                break;
            default:

                break;
        }

        /**
         ** save documents
         */
        $requirements = ProcedureRequirement::select('id')->get();
        foreach ($requirements  as  $requirement) {
            if ($request->input('document' . $requirement->id) == 'checked') {
                $submit = new EcoComSubmittedDocument();
                $submit->economic_complement_id = $economic_complement->id;
                $submit->procedure_requirement_id = $requirement->id;
                $submit->reception_date = date('Y-m-d');
                $submit->comment = $request->input('comment' . $requirement->id);
                $submit->save();
            }
        }
        if ($request->aditional_requirements) {
            foreach ($request->aditional_requirements  as  $requirement) {
                $submit = new EcoComSubmittedDocument();
                $submit->economic_complement_id = $economic_complement->id;
                $submit->procedure_requirement_id = $requirement;
                $submit->reception_date = date('Y-m-d');
                $submit->comment = null;
                $submit->save();
            }
        }
        return redirect()->action('EconomicComplementController@show', ['id' => $economic_complement->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('read', new EconomicComplement());
        $economic_complement = EconomicComplement::with(['wf_state:id,name', 'workflow:id,name', 'eco_com_modality:id,name,shortened'])->findOrFail($id);
        $affiliate = $economic_complement->affiliate;
        $degrees = Degree::all();
        $categories = Category::all();

        $states = ProcedureState::all();
        $pension_entities = PensionEntity::all();

        /*
        * for affiliate info
        */
        $cities = City::get();
        $cities_pluck = City::all()->pluck('first_shortened', 'id');
        $birth_cities = City::all()->pluck('name', 'id');
        $is_editable = false;
        $affiliate->phone_number = explode(',', $affiliate->phone_number);
        $affiliate->cell_phone_number = explode(',', $affiliate->cell_phone_number);
        if (!sizeOf($affiliate->address) > 0) {
            $affiliate->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }
        //police info
        $affiliate_states = AffiliateState::all()->pluck('name', 'id');

        /**
         ** for beneficiary info
         */
        $eco_com_beneficiary = $economic_complement->eco_com_beneficiary;
        $eco_com_beneficiary->phone_number = explode(',', $eco_com_beneficiary->phone_number);
        $eco_com_beneficiary->cell_phone_number = explode(',', $eco_com_beneficiary->cell_phone_number);
        if (!sizeOf($eco_com_beneficiary->address) > 0) {
            $eco_com_beneficiary->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }

        $user = User::find(Auth::user()->id);
        $procedure_types = ProcedureType::where('module_id', 2)->get();
        $procedure_requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
            ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
            ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
            ->orderBy('procedure_requirements.number', 'ASC')
            ->get();
        $procedure_modalities = ProcedureModality::where('procedure_type_id', '<=', '8')->select('id', 'name', 'procedure_type_id')->get();
        // $observation_types = ObservationType::where('module_id',3)->get();
        $submitted = EcoComSubmittedDocument::select('eco_com_submitted_documents.id', 'procedure_requirements.number', 'eco_com_submitted_documents.procedure_requirement_id', 'eco_com_submitted_documents.comment', 'eco_com_submitted_documents.is_valid')
            ->leftJoin('procedure_requirements', 'eco_com_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
            ->orderby('procedure_requirements.number', 'ASC')
            ->where('eco_com_submitted_documents.economic_complement_id', $id);

        /**
         ** for validation and submit
         */
        $rol = Util::getRol();
        $module = Role::find($rol->id)->module;
        $wf_current_state = WorkflowState::where('role_id', $rol->id)->where('module_id', '=', $module->id)->first();
        $can_validate = optional($wf_current_state)->id == $economic_complement->wf_current_state_id;
        $can_cancel = ($economic_complement->user_id == $user->id && $economic_complement->inbox_state == true);

        $wf_sequences_back = DB::table("wf_states")
            ->where("wf_states.module_id", "=", $module->id)
            ->where('wf_states.sequence_number', '<', WorkflowState::find($economic_complement->wf_current_state_id)->sequence_number)
            ->select(
                'wf_states.id as wf_state_id',
                'wf_states.first_shortened as wf_state_name'
            )
            ->get();

        /**
         ** for records
         */
        $eco_com_records =  EconomicComplementRecord::where('economic_complement_id', $id)->orderBy('id', 'desc')->get();
        $workflow_records = $economic_complement->wf_records()->orderBy('date', 'desc')->get();
        $first_wf_state = EconomicComplementRecord::where('economic_complement_id', $id)->whereRaw("message like '%creó el tr%'")->first();
        if ($first_wf_state) {
            $re = '/(?<= usuario )(.*)(?= cr.* )/mi';
            $str = $first_wf_state->message;
            preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
            $user_name = $matches[0][0];
            if (User::where('username', '=', $user_name)->first()) {
                $rol = User::where('username', '=', $user_name)->first()->roles->first();
                $first_wf_state = WorkflowState::where('role_id', $rol->id)->first();
            }
        }

        /**
         ** for observations
         */
        $observation_types = ObservationType::where('module_id', Util::getRol()->module_id)->where('type', 'T')->get();
        // $affiliate_observations = AffiliateObservation::where('affiliate_id', $economic_complement->affiliate_id)->get();
        // foreach($affiliate_observations as $observation){
        //     if($observation->observationType->type=='AT')
        //     {
        //         $eco_com_observation = EconomicComplementObservation::where('economic_complement_id',$economic_complement->id)
        //         ->where('observation_type_id',$observation->observation_type_id)
        //         ->first();
        //         if(!$eco_com_observation)
        //         {
        //             $new_observation = ObservationType::find($observation->observation_type_id);
        //             $observations_types->push($new_observation);
        //             // ($observations_types,$new_observation);   
        //         }
        //     }
        // }

        /**
         ** Permissions
         */
        $permissions = Util::getPermissions(
            ObservationType::class,
            EconomicComplement::class
        );
        $data = [
            'economic_complement' => $economic_complement,
            'affiliate' => $affiliate,
            'states' => $states,
            'pension_entities' => $pension_entities,
            'cities' => $cities,
            'cities_pluck' => $cities_pluck,
            'birth_cities' => $birth_cities,
            'is_editable' => $is_editable,
            'eco_com_beneficiary' => $eco_com_beneficiary,

            'degrees' => $degrees,
            'categories' => $categories,
            'affiliate_states' => $affiliate_states,

            'user' => $user,
            'procedure_modalities' => $procedure_modalities,
            'requirements' => $procedure_requirements,
            'procedure_types' => $procedure_types,
            'submitted' =>  $submitted->pluck('eco_com_submitted_documents.procedure_requirement_id', 'procedure_requirements.number'),
            'submit_documents' => $submitted->get(),

            'can_validate' => $can_validate,
            'can_cancel' => $can_cancel,
            'wf_sequences_back' => $wf_sequences_back,

            'eco_com_records' =>  $eco_com_records,
            'workflow_records' =>  $workflow_records,
            'first_wf_state' =>  $first_wf_state,
            'observation_types' =>  $observation_types,

            'permissions' =>  $permissions,
        ];
        return view('eco_com.show', $data);
    }
    public function updateAffiliatePoliceEcoCom(Request $request)
    {
        try {
            $this->authorize('update', new EconomicComplement());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar el tramite'],
            ], 403);
        }
        $affiliate = Affiliate::where('id', '=', $request->id)->first();
        // $this->authorize('update', $affiliate);
        $affiliate->date_entry = Util::verifyMonthYearDate($request->date_entry) ? Util::parseMonthYearDate($request->date_entry) : $request->date_entry;
        $affiliate->item = $request->item;
        $affiliate->category_id = $request->category_id;
        $service_year = $request->service_years;
        $service_month = $request->service_months;
        Log::info($service_year);
        Log::info($service_month);
        if ($service_year > 0 || $service_month > 0) {
            if ($service_month > 0) {
                $service_year++;
            }
            $category = Category::where('from', '<=', $service_year)
                ->where('to', '>=', $service_year)
                ->first();
            if ($category) {
                $affiliate->category_id = $category->id;
                $affiliate->service_years = $request->service_years;
                $affiliate->service_months = $request->service_months;
            }
        }
        Log::info($request->all());
        $affiliate->degree_id = $request->degree_id;
        $affiliate->pension_entity_id = $request->pension_entity_id;
        $affiliate->save();
        $economic_complement = EconomicComplement::find($request->eco_com_id);
        $economic_complement->degree_id = $request->degree_id;
        $economic_complement->category_id = $affiliate->category_id;
        $economic_complement->save();
        Log::info('update affiliate and eco com');
        return array('affiliate' => $affiliate);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateInformation(Request $request)
    {
        try {
            $this->authorize('update', new EconomicComplement());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar el tramite'],
            ], 403);
        }
        $economic_complement = EconomicComplement::findOrFail($request->id);
        // $economic_complement->degree_id = $request->degree_id;
        // $economic_complement->category_id = $request->category_id;
        $economic_complement->city_id = $request->city_id;
        $economic_complement->reception_date = $request->reception_date;
        $economic_complement->save();
        /**
         * update affiliate info
         */
        // $affiliate = $economic_complement->affiliate;
        // $affiliate->degree_id = $request->degree_id;
        // $affiliate->category_id = $request->category_id;
        // $affiliate->pension_entity_id = $request->pension_entity_id;
        // $affiliate->save();
        return $economic_complement;
    }
    public function firstStep()
    {
        $this->authorize('create', new EconomicComplement());
        $cities = City::all();
        $data = [
            'cities' => $cities,
        ];
        return view('eco_com.first_step', $data);
    }

    public function getReceptionType(Request $request)
    {
        $reception_type_id = 1;
        if (!$request->modality_id) {
            return $reception_type_id;
        }
        if ($request->last_eco_com_id) {
            $eco_com = EconomicComplement::find($request->last_eco_com_id);
            if ($eco_com->eco_com_modality->eco_com_type_id == $request->modality_id) {
                $reception_type_id = 2;
            }
        }
        return $reception_type_id;
    }
    public function getTypeBeneficiary(Request $request)
    {
        if (!$request->affiliate_id) {
            return null;
        }
        $affiliate = Affiliate::find($request->affiliate_id);
        if ($request->last_eco_com_id) {
            $eco_com = EconomicComplement::find($request->last_eco_com_id);
            if ($eco_com->eco_com_modality->eco_com_type_id == $request->modality_id) {
                $eco_com_beneficiary = $eco_com->eco_com_beneficiary()->with('address')->first();
                if ($eco_com_beneficiary) {
                    if (!sizeOf($eco_com_beneficiary->address) > 0) {
                        $eco_com_beneficiary->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
                    }
                    $eco_com_beneficiary->phone_number = $this->parsePhone($eco_com_beneficiary->phone_number ?? '');
                    $eco_com_beneficiary->cell_phone_number = $this->parsePhone($eco_com_beneficiary->cell_phone_number ?? '');
                } else {
                    $eco_com_beneficiary = new EcoComBeneficiary();
                }
                return $eco_com_beneficiary;
            }
        }
        switch ($request->modality_id) {
            case 1:
                $affiliate->load([
                    'address'
                ]);
                $affiliate->phone_number = $this->parsePhone($affiliate->phone_number) ?? '';
                $affiliate->cell_phone_number = $this->parsePhone($affiliate->cell_phone_number) ?? '';
                return $affiliate;
                break;
            case 2:
                $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
                if (!$spouse) {
                    // $spouse = new Spouse();
                    $spouse = new EcoComBeneficiary();
                }
                // $spouse->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
                // $spouse->phone_number = $this->parsePhone($spouse->phone_number ?? '') ;
                // $spouse->cell_phone_number = $this->parsePhone($spouse->cell_phone_number ?? '') ;
                $spouse->phone_number = [array('value' => null)];
                $spouse->cell_phone_number = [array('value' => null)];
                return $spouse;
                break;
            default:
                $ben = new EcoComBeneficiary();
                $ben->phone_number = [array('value' => null)];
                $ben->cell_phone_number = [array('value' => null)];
                return $ben;
                break;
        }
        return null;
    }
    public function parsePhone($phones)
    {
        $array_phone = [];
        foreach (explode(',', $phones) as $phone) {
            $json_phone = new \stdClass;
            $json_phone->value = $phone;
            array_push($array_phone, $json_phone);
        }
        return $array_phone;
    }
    public function editRequirements(Request $request, $id)
    {
        try {
            $this->authorize('update', new EconomicComplement());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar el tramite'],
            ], 403);
        }
        $documents = EcoComSubmittedDocument::select('procedure_requirements.number', 'eco_com_submitted_documents.procedure_requirement_id')
            ->leftJoin('procedure_requirements', 'eco_com_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
            ->orderby('procedure_requirements.number', 'ASC')
            ->where('eco_com_submitted_documents.economic_complement_id', $id)
            ->where('procedure_requirements.number', '>', '0')
            ->pluck('eco_com_submitted_documents.procedure_requirement_id', 'procedure_requirements.number');
        $num = $num2 = 0;

        foreach ($request->requirements as $requirement) {
            $from = $to = 0;
            $comment = null;
            for ($i = 0; $i < count($requirement); $i++) {
                $from = $requirement[$i]['number'];
                if ($requirement[$i]['status'] == true) {
                    $to = $requirement[$i]['id'];
                    $comment = $requirement[$i]['comment'];
                    $doc = EcoComSubmittedDocument::where('economic_complement_id', $id)->where('procedure_requirement_id', $documents[$from])->first();
                    $doc->procedure_requirement_id = $to;
                    $doc->comment = $comment;
                    $doc->save();
                }
            }
        }

        $procedure_requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
            ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
            ->where('procedure_requirements.number', '0')
            ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
            ->orderBy('procedure_requirements.number', 'ASC')
            ->get();

        $eco_com = EconomicComplement::select('id', 'eco_com_modality_id')->find($id);
        $aditional =  $request->aditional_requirements;
        $num = "";
        foreach ($procedure_requirements as $requirement) {
            $needle = EcoComSubmittedDocument::where('economic_complement_id', $id)
                ->where('procedure_requirement_id', $requirement->id)
                ->first();
            if (isset($needle)) {
                if (!in_array($requirement->id, $aditional)) {
                    $num .= $requirement->id . ' ';
                    $needle->delete();
                    $needle->forceDelete();
                }
            } else {
                if (in_array($requirement->id, $aditional)) {
                    $submit = new EcoComSubmittedDocument();
                    $submit->economic_complement_id = $eco_com->id;
                    $submit->procedure_requirement_id = $requirement->id;
                    $submit->reception_date = date('Y-m-d');
                    $submit->comment = "";
                    $submit->save();
                }
            }
        }

        return $num;
    }
    public function getEcoCom($id)
    {
        try {
            $this->authorize('read', new EconomicComplement());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para ver el tramite'],
            ], 403);
        }
        $rol = Util::getRol();
        $discount_type_id = null;
        switch ($rol->id) {
            case 7: //contabiliadad
                $discount_type_id = 4;
                break;
            case 16: //prestamo
                $discount_type_id = 5;
                break;
            case 4: // complemento
                $discount_type_id = 6;
                break;
        }
        $eco_com = EconomicComplement::with('discount_types')->findOrFail($id);
        $eco_com->discount_amount = optional(optional($eco_com->discount_types()->where('discount_type_id', $discount_type_id)->first())->pivot)->amount;
        return $eco_com;
    }
    public function updateRents(Request $request)
    {
        try {
            $this->authorize('update', new EconomicComplement());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar el tramite'],
            ], 403);
        }
        $economic_complement = EconomicComplement::with('discount_types')->find($request->id);
        if ($economic_complement->eco_com_state->eco_com_state_type_id == 1 || $economic_complement->eco_com_state->eco_com_state_type_id == 6) {
            $eco_com_state = $economic_complement->eco_com_state;
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => ['No se puede modificar las rentas del trámite ' . $economic_complement->code . 'porque se encuentra en estado de ' . $eco_com_state->name],
            ], 422);
        }
        if ($request->pension_entity_id == 5) {
            $economic_complement->sub_total_rent = Util::parseMoney($request->sub_total_rent);
            $economic_complement->reimbursement = Util::parseMoney($request->reimbursement);
            $economic_complement->dignity_pension = Util::parseMoney($request->dignity_pension);
            $economic_complement->aps_disability = Util::parseMoney($request->aps_total_disability);
            $economic_complement->aps_total_fsa = null;
            $economic_complement->aps_total_cc = null;
            $economic_complement->aps_total_fs = null;
        } else {
            $economic_complement->aps_total_fsa = Util::parseMoney($request->aps_total_fsa);
            $economic_complement->aps_total_cc = Util::parseMoney($request->aps_total_cc);
            $economic_complement->aps_total_fs = Util::parseMoney($request->aps_total_fs);
            $economic_complement->aps_disability = Util::parseMoney($request->aps_total_disability);
            $economic_complement->sub_total_rent = null;
            $economic_complement->reimbursement = null;
            $economic_complement->dignity_pension = null;
        }
        $economic_complement->save();

        if (Gate::allows('qualify', $economic_complement)) {
            return $economic_complement->qualify();
        }
        return $economic_complement;
    }
    public function saveAmortization(Request $request)
    {
        try {
            $this->validate($request, [
                'amount' => 'required|numeric|min:1',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        Log::info($request->all());
        $eco_com = EconomicComplement::find($request->id);
        if ($eco_com->eco_com_state->eco_com_state_type_id == 1 || $eco_com->eco_com_state->eco_com_state_type_id == 6) {
            $eco_com_state = $eco_com->eco_com_state;
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => ['No se puede realizar la amortización porque el trámite ' . $eco_com->code . ' se encuentra en estado de ' . $eco_com_state->name],
            ], 422);
        }
        $rol = Util::getRol();
        $discount_type_id = null;
        switch ($rol->id) {
            case 7: //contabiliadad
                $discount_type_id = 4;
                break;
            case 16: //prestamo
                $discount_type_id = 5;
                break;
            case 4: // complemento
                $discount_type_id = 6;
                break;
        }
        $discount_type = DiscountType::findOrFail($discount_type_id);
        if ($eco_com->discount_types->contains($discount_type->id)) {
            $eco_com->discount_types()->updateExistingPivot($discount_type->id, ['amount' => $request->amount, 'date' => now(), 'message' => $request->message]);
        } else {
            $eco_com->discount_types()->save($discount_type, ['amount' => $request->amount, 'date' => now(), 'message' => $request->message]);
        }
        //detach
        // if ($eco_com->discount_types->contains($discount_type->id)) {
        //     $eco_com->discount_types()->detach($discount_type->id);
        // }
        $record = new EconomicComplementRecord();
        $record->user_id = Auth::user()->id;
        $record->economic_complement_id = $eco_com->id;
        $record->message = "El usuario " . Auth::user()->username  . " amortizó " . $request->amount . ".";
        $record->save();

        $eco_com->discount_amount = optional(optional($eco_com->discount_types()->where('discount_type_id', $discount_type_id)->first())->pivot)->amount;
        return $eco_com;
        // case 4: //complemento
        // $start_procedure = EconomicComplementProcedure::where('id','=', 2)->first();
        //     $complemento = EconomicComplement::where('id', $request->id_complemento)->first();
        //     $complemento->amount_replacement = $request->amount_amortization;
        //     $complemento->save();
        //     $sum = 0;
        //     while ($start_procedure) {
        //         $eco_com = $start_procedure->economic_complements()->where('affiliate_id', '=', $complemento->affiliate_id)->first();
        //         if ($eco_com) {
        //             if ($eco_com->amount_replacement) {
        //                 $sum += $eco_com->amount_replacement;
        //             }
        //         }
        //         $start_procedure = EconomicComplementProcedure::where('id', '=', Util::semesternext(Carbon::parse($start_procedure->year)->year, $start_procedure->semester))->first();
        //         Log::info("whille");
        //     }
        //     $devolution = Devolution::where('affiliate_id', '=', $complemento->affiliate_id)->where('observation_type_id', '=', 13)->first();
        //     if ($devolution) {
        //         $devolution->balance = $devolution->total - $sum;
        //         $devolution->save();
        //     }
        //     break;
        // Session::flash('message', 'Se guardo la Amortización.');

        // if ($complemento->total_rent > 0) {
        //     EconomicComplement::calculate($complemento, $complemento->total_rent, $complemento->sub_total_rent, $complemento->reimbursement, $complemento->dignity_pension, $complemento->aps_total_fsa, $complemento->aps_total_cc, $complemento->aps_total_fs, $complemento->aps_disability);
        //     $complemento->save();
        // }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->authorize('delete', new EconomicComplement());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para eliminar el tramite'],
            ], 403);
        }
        if ($id) {
            $economic_complement = EconomicComplement::find($id);
            $economic_complement->code = $economic_complement->code . 'A';
            $economic_complement->save();
            $economic_complement->delete();
            return response()->json([
                'message' => 'deleted',
            ], 204);
        }
        return [];
    }
    // public function averages()
    // {
    //     $year_list = EcoComProcedure::orderByDesc('year')->pluck('year')->map(function ($item, $key) {
    //         return Carbon::parse($item)->year;
    //     })->unique()->toArray();
    //     $year_list = array_combine($year_list, $year_list);
    //     $semester_list = EcoComProcedure::all()->pluck('semester')->unique()->toArray();
    //     $semester_list = array_combine($semester_list, $semester_list);

    //     $data = [
    //         'year_list' => $year_list,
    //         'semester_list' => $semester_list,
    //     ];
    //     return view('eco_com.average', $data);
    // }
    public function getAverageData(Request $request)
    {
        $year = $request->year;
        $semester = $request->semester;
        if (!$request->has('year') || !$request->has('semester')) {
            $procedure = EcoComProcedure::find(Util::getEcoComCurrentProcedure()->first());
            $year = Carbon::parse($procedure->year)->year;
            $semester = $procedure->semester;
        }
        $average_list = EcoComRent::select(DB::raw("degrees.shortened as degree, eco_com_types.name as type,eco_com_rents.minor as rmin,eco_com_rents.higher as rmax, eco_com_rents.average as average "))
            ->leftJoin('eco_com_types', 'eco_com_rents.eco_com_type_id', '=', 'eco_com_types.id')
            ->leftJoin('degrees', 'eco_com_rents.degree_id', '=', 'degrees.id')
            ->whereYear('eco_com_rents.year', '=', $year)
            ->where('eco_com_rents.semester', '=', $semester)
            ->orderBy('degrees.correlative', 'ASC')
            ->orderBy('eco_com_types.id', 'ASC');

        return Datatables::of($average_list)
            ->addColumn('degree', function ($average_list) {
                return $average_list->degree;
            })
            ->editColumn('type', function ($average_list) {
                return $average_list->type;
            })
            ->editColumn('rmin', function ($average_list) {
                return $average_list->rmin;
            })
            ->editColumn('rmax', function ($average_list) {
                return $average_list->rmax;
            })
            ->editColumn('average', function ($average_list) {
                return $average_list->average;
            })
            ->make(true);
    }
    public function printAverage()
    {
        return null;
    }
    public function qualificationParameters()
    {
        // averages
        $year_list = EcoComProcedure::orderByDesc('year')->pluck('year')->map(function ($item, $key) {
            return Carbon::parse($item)->year;
        })->unique()->toArray();
        $year_list = array_combine($year_list, $year_list);
        $semester_list = EcoComProcedure::all()->pluck('semester')->unique()->toArray();
        $semester_list = array_combine($semester_list, $semester_list);

        // complementary factor
        $procedure = EcoComProcedure::find(Util::getEcoComCurrentProcedure()->first());
        $year = Carbon::parse($procedure->year)->year;
        $semester = $procedure->semester;
        if (ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 1)->first()) {
            $complementary_factor = ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 1)->first();
            $cf1_old_age = $complementary_factor->old_age;
            $cf1_widowhood = $complementary_factor->widowhood;
        } else {
            $cf1_old_age = "";
            $cf1_widowhood = "";
        }
        if (ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 2)->first()) {
            $complementary_factor = ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 2)->first();
            $cf2_old_age = $complementary_factor->old_age;
            $cf2_widowhood = $complementary_factor->widowhood;
        } else {
            $cf2_old_age = "";
            $cf2_widowhood = "";
        }

        if (ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 3)->first()) {
            $complementary_factor = ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 3)->first();
            $cf3_old_age = $complementary_factor->old_age;
            $cf3_widowhood = $complementary_factor->widowhood;
        } else {
            $cf3_old_age = "";
            $cf3_widowhood = "";
        }

        if (ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 4)->first()) {
            $complementary_factor = ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 4)->first();
            $cf4_old_age = $complementary_factor->old_age;
            $cf4_widowhood = $complementary_factor->widowhood;
        } else {
            $cf4_old_age = "";
            $cf4_widowhood = "";
        }

        if (ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 5)->first()) {
            $complementary_factor = ComplementaryFactor::whereYear('year', '=', $year)->where('semester', '=', $semester)->where('hierarchy_id', '=', 5)->first();
            $cf5_old_age = $complementary_factor->old_age;
            $cf5_widowhood = $complementary_factor->widowhood;
        } else {
            $cf5_old_age = "";
            $cf5_widowhood = "";
        }

        /**
         ** Permissions
         */

        $permissions = Util::getPermissions(
            EcoComProcedure::class
        );
        $data = [
            'complementary_factor' => new ComplementaryFactor(),
            'year' => $year,
            'semester' => $semester,
            'cf1_old_age' => $cf1_old_age,
            'cf1_widowhood' => $cf1_widowhood,
            'cf2_old_age' => $cf2_old_age,
            'cf2_widowhood' => $cf2_widowhood,
            'cf3_old_age' => $cf3_old_age,
            'cf3_widowhood' => $cf3_widowhood,
            'cf4_old_age' => $cf4_old_age,
            'cf4_widowhood' => $cf4_widowhood,
            'cf5_old_age' => $cf5_old_age,
            'cf5_widowhood' => $cf5_widowhood,

            'year_list' => $year_list,
            'semester_list' => $semester_list,

            'permissions' => $permissions,
        ];

        return view('eco_com.qualification_parameters', $data);
    }
}