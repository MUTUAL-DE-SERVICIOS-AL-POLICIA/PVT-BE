<?php

namespace Muserpol\Http\Controllers;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\Kinship;
use Muserpol\Models\City;
use Muserpol\Models\Degree;
use Auth;
use Validator;
use Muserpol\Models\Address;
use Muserpol\Models\Spouse;
use Muserpol\Models\QuotaAidMortuary\QuotaAidProcedure;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidSubmittedDocument;
use Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisorBeneficiary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidLegalGuardian;
use Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiaryLegalGuardian;
use Muserpol\Helpers\Util;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\AidCommitment;
use Muserpol\User;
use Muserpol\Models\ObservationType;
class QuotaAidMortuaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {        
        return View("quota_aid.index");
    }
    public function getAllQuotaAid(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';          
        $last_name = strtoupper($request->last_name) ?? '';
        $first_name = strtoupper($request->first_name) ?? '';
        $code = $request->code ?? '';
        $modality = strtoupper($request->modality) ?? '';
         

        $total = QuotaAidMortuary::select('quota_aid_mortuaries.id')
                                ->leftJoin('affiliates','quota_aid_mortuaries.affiliate_id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','quota_aid_mortuaries.procedure_modality_id','=','procedure_modalities.id')
                                ->leftJoin('workflows','quota_aid_mortuaries.workflow_id','=','workflows.id')                               
                                ->where('quota_aid_mortuaries.code','LIKE',$code.'%')                                
                                ->where('affiliates.first_name','LIKE',$first_name.'%')
                                ->where('affiliates.last_name','LIKE',$last_name.'%')                             
                                ->count();
        //dd($total);
         $quota_aid_mortuaries = QuotaAidMortuary::select('quota_aid_mortuaries.id','affiliates.first_name as first_name','affiliates.last_name as last_name','procedure_modalities.name as modality','workflows.name as workflow','quota_aid_mortuaries.code','quota_aid_mortuaries.reception_date','quota_aid_mortuaries.total')
                                ->leftJoin('affiliates','quota_aid_mortuaries.affiliate_id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','quota_aid_mortuaries.procedure_modality_id','=','procedure_modalities.id')
                                ->leftJoin('workflows','quota_aid_mortuaries.workflow_id','=','workflows.id')                               
                                ->where('quota_aid_mortuaries.code','LIKE',$code.'%')                                
                                ->where('affiliates.first_name','LIKE',$first_name.'%')
                                ->where('affiliates.last_name','LIKE',$last_name.'%')                                
                                ->skip($offset)
                                ->take($limit)
                                ->orderBy($sort,$order)
                                ->get();             
       // dd($quota_aid_mortuaries);
        return response()->json(['quota_aid_mortuaries' => $quota_aid_mortuaries->toArray(),'total'=>$total]);
    }

 
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $first_name = $request->beneficiary_first_name;
        $second_name = $request->beneficiary_second_name;
        $last_name = $request->beneficiary_last_name;
        $mothers_last_name = $request->beneficiary_mothers_last_name;
        $surname_husband = $request->surname_husband;
        $identity_card = $request->beneficiary_identity_card;
        $city_id = $request->beneficiary_city_identity_card;
        $birth_date = $request->beneficiary_birth_date;
        $kinship = $request->beneficiary_kinship;

        $requirements = ProcedureRequirement::select('id')->get();
        $affiliate = Affiliate::find($request->affiliate_id);                
        $procedure = QuotaAidProcedure::where('hierarchy_id',$affiliate->degree->hierarchy_id)->where('procedure_modality_id',$request->quota_aid_modality)->select('id')->first();        
        $validator = Validator::make($request->all(), [
            //'applicant_first_name' => 'required|max:5',            
        ]);                
        //custom this validator
        $validator->after(function($validator){
            if(false)                   
                $validator->errors()->add('Modalidad', 'el campo modalidad no puede ser tramitada este mes');            
        });        
        if($validator->fails()){
            return $validator->errors();            
        }

        $rules = [];
        $biz_rules = [];
        
        $has_quota_aid = false;
        $quota_aid = QuotaAidMortuary::where('affiliate_id',$affiliate->id)->where('code','NOT LIKE','%A')->first();
        if(isset($quota_aid->id)) {
            $has_quota_aid = true;
            return $quota_aid;
            return "ya tiene un tramite";
            // $biz_rules = [
            //     'quota_aid_double'
            // ];
            // $code = Util::getNextCode ("");
        }
        else {
            $quota_aid = QuotaAidMortuary::select('id','code')->orderBy('id','desc')->first();
            $code = Util::getNextCode ($quota_aid->code);
        }
            
        $modality = ProcedureModality::find($request->quota_aid_modality);
        
        
        $quota_aid = new QuotaAidMortuary();
        //$this->authoriza('create', $quota_aid);
        $quota_aid->user_id = Auth::user()->id;
        $quota_aid->affiliate_id = $request->affiliate_id;
        $quota_aid->procedure_modality_id = $request->quota_aid_modality;
        $quota_aid->quota_aid_procedure_id = $procedure->id;
        $quota_aid->city_start_id = Auth::user()->city_id;
        $quota_aid->city_end_id = Auth::user()->city_id;        
        $quota_aid->code = $code;
        $quota_aid->reception_date = date('Y-m-d');
        $quota_aid->workflow_id = $modality->procedure_type_id;
        $quota_aid->wf_state_current_id = 19;
        $quota_aid->subtotal = 0;
        $quota_aid->total = 0;
        $quota_aid->save();
        
        foreach ($requirements  as  $requirement)
        {
            if($request->input('document'.$requirement->id) == 'checked')
            {
                $submit = new QuotaAidSubmittedDocument();
                $submit->quota_aid_mortuary_id = $quota_aid->id;
                $submit->procedure_requirement_id = $requirement->id;
                $submit->reception_date = date('Y-m-d');
                $submit->comment = $request->input('comment'.$requirement->id);                
                $submit->save();
            }                
        }
        $account_type = $request->input('accountType');    

        $beneficiary = new QuotaAidBeneficiary();
        $beneficiary->quota_aid_mortuary_id = $quota_aid->id;
        $beneficiary->city_identity_card_id = $request->applicant_city_identity_card;
        $beneficiary->kinship_id = $request->applicant_kinship;
        $beneficiary->identity_card = mb_strtoupper($request->applicant_identity_card);
        $beneficiary->last_name = mb_strtoupper($request->applicant_last_name);
        $beneficiary->mothers_last_name = mb_strtoupper($request->applicant_mothers_last_name);
        $beneficiary->first_name = mb_strtoupper($request->applicant_first_name);
        $beneficiary->second_name = mb_strtoupper($request->applicant_second_name);
        $beneficiary->surname_husband = mb_strtoupper($request->applicant_surname_husband);
        $beneficiary->gender = $request->applicant_gender;
        $beneficiary->phone_number = trim(implode(",", $request->applicant_phone_number));
        $beneficiary->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number));
        $beneficiary->type = "S";
        $beneficiary->save();
                
        if($account_type == '2')
        {
            $advisor = new QuotaAidAdvisor();
            //$advisor->retirement_fund_id = $retirement_found->id;
            $advisor->city_identity_card_id = $request->applicant_city_identity_card;
            $advisor->kinship_id = null;
            $advisor->identity_card = $request->applicant_identity_card;
            $advisor->last_name = $request->applicant_last_name;
            $advisor->mothers_last_name = $request->applicant_mothers_last_name;
            $advisor->first_name = $request->applicant_first_name;
            $advisor->second_name = $request->applicant_second_name;
            $advisor->surname_husband = $request->applicant_surname_husband;        
            $advisor->gender = "M";                    
            $advisor->phone_number = trim(implode(",", $request->applicant_phone_number));
            $advisor->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number));
            $advisor->name_court = $request->advisor_name_court;            
            $advisor->resolution_number = $request->advisor_resolution_number;
            $advisor->resolution_date = $request->advisor_resolution_date;
            $advisor->type = "Natural";
            $advisor->save();
            
            $advisor_beneficiary = new QuotaAidAdvisorBeneficiary();
            $advisor_beneficiary->ret_fun_beneficiary_id = $beneficiary->id;
            $advisor_beneficiary->ret_fun_advisor_id = $advisor->id;
            $advisor_beneficiary->save();
        }
        
        if($account_type == '3')
        {
            $legal_guardian = new QuotaAidLegalGuardian();
            $legal_guardian->retirement_fund_id = $retirement_found->id;
            $legal_guardian->city_identity_card_id = $request->applicant_city_identity_card;            
            $legal_guardian->identity_card = $request->applicant_identity_card  ;
            $legal_guardian->last_name = $request->applicant_last_name;
            $legal_guardian->mothers_last_name = $request->applicant_mothers_last_name;
            $legal_guardian->first_name = $request->applicant_first_name;
            $legal_guardian->second_name = $request->applicant_second_name;
            $legal_guardian->surname_husband = $request->applicant_surname_husband;        
            //$legal_guardian->gender = "M";                    
            $legal_guardian->phone_number = trim(implode(",", $request->applicant_phone_number));
            $legal_guardian->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number));
            $legal_guardian->number_authority = $request->legal_guardian_number_authority;            
            $legal_guardian->notary_of_public_faith = $request->legal_guardian_notary_of_public_faith;
            $legal_guardian->notary = $request->legal_guardian_notary;
            $legal_guardian->save();
            
            $beneficiary_legal_guardian = new RetFunLegalGuardianBeneficiary();
            $beneficiary_legal_guardian->ret_fun_beneficiary_id = $beneficiary->id;
            $beneficiary_legal_guardian->ret_fun_legal_guardian_id = $legal_guardian->id;
            $beneficiary_legal_guardian->save();
            //$beneficiary->type = "N";            
        }
        
        
        if ($request->beneficiary_zone || $request->beneficiary_street || $request->beneficiary_number_address) {            
            $address = new Address();
            $address->city_address_id = 1;
            $address->zone = $request->beneficiary_zone;
            $address->street = $request->beneficiary_street;
            $address->number_address = $request->beneficiary_number_address;
            $address->save();

            $beneficiary->address()->save($address);
        }
        
        // crear relacion
        //borrar esto
        // $address_rel = new RetFunAddressBeneficiary();
        // $address_rel->ret_fun_beneficiary_id = $beneficiary->id;
        // $address_rel->address_id = $address->id;
        // $address_rel->save();
        
       

        for($i=0;is_array($first_name) &&  $i<sizeof($first_name);$i++){
            if($first_name[$i] != "" && $last_name[$i] != ""){
                $beneficiary = new QuotaAidBeneficiary();
                $beneficiary->retirement_fund_id = $retirement_found->id;
                $beneficiary->city_identity_card_id = $city_id[$i];
                $beneficiary->kinship_id = $kinship[$i];
                $beneficiary->identity_card = strtoupper($identity_card[$i]);
                $beneficiary->last_name = strtoupper($last_name[$i]);
                $beneficiary->mothers_last_name = strtoupper($mothers_last_name[$i]);
                $beneficiary->first_name = strtoupper($first_name[$i]);
                $beneficiary->second_name = strtoupper($second_name[$i]);
                $beneficiary->surname_husband = strtoupper($surname_husband[$i]);
                $beneficiary->birth_date = $birth_date[$i];
                $beneficiary->gender = strtoupper(trim($gender[$i]));
                $beneficiary->type = "N";
                $beneficiary->save();                
            }        
        }
                
        $data = [
            
        ];        
        return redirect('quota_aid/'.$quota_aid->id);           
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return \Illuminate\Http\Response
     */
    //public function show(RetirementFund $retirementFund)
    public function show($id)
    {
        
//        $retirement_fund = RetirementFund::find($id);
//        
//        $affiliate = Affiliate::find($retirement_fund->affiliate_id);
//        
//        $beneficiaries = RetFunBeneficiary::where('retirement_fund_id',$retirement_fund->id)->orderBy('type','desc')->get();
//        
//        $applicant = RetFunBeneficiary::where('type','S')->where('retirement_fund_id',$retirement_fund->id)->first();
//        
//        $beneficiary_avdisor = RetFunAdvisorBeneficiary::where('ret_fun_beneficiary_id',$applicant->id)->first();
//        
//        if(isset($beneficiary_avdisor->id))
//            $advisor= RetFunAdvisor::find($beneficiary_avdisor->ret_fun_advisor_id);
//        else
//            $advisor = new RetFunAdvisor();
//        
//        $beneficiary_guardian = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id',$applicant->id)->first();
//        
//        if(isset($beneficiary_guardian->id))
//            $guardian = RetFunLegalGuardian::find($beneficiary_guardian->ret_fun_legal_guardian_id);
//        else 
//            $guardian = new RetFunLegalGuardian();                
//        
//        $data = [
//            'retirement_fund' => $retirement_fund,
//            'affiliate' =>  $affiliate,
//            'beneficiaries' =>  $beneficiaries,
//            'applicant' => $applicant,
//            'advisor'  =>  $advisor,
//            'legal_guardian'    =>  $guardian,            
//        ];
//        
//        return view('ret_fun.show',$data);

        
        $quota_aid = QuotaAidMortuary::find($id);

        //$this->authorize('view', $retirement_fund);

        $affiliate = Affiliate::find($quota_aid->affiliate_id);
        if (!sizeOf($affiliate->address) > 0) {
            $affiliate->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }

        $beneficiaries = QuotaAidBeneficiary::where('quota_aid_mortuary_id',$quota_aid->id)->with(['kinship', 'city_identity_card'])->orderByDesc('type')->orderBy('id')->get();
        
        // foreach ($beneficiaries as $b) {
        //     $b->phone_number=explode(',',$b->phone_number);
        //     $b->cell_phone_number=explode(',',$b->cell_phone_number);
        //     if(! sizeOf($b->address) > 0 && $b->type == 'S'){
        //         $b->address[]= array('zone' => null, 'street'=>null, 'number_address'=>null);
        //     }
        // }
        
        $applicant = QuotaAidBeneficiary::where('type','S')->where('quota_aid_mortuary_id',$quota_aid->id)->first();        
        
        $beneficiary_avdisor = QuotaAidAdvisorBeneficiary::where('quota_aid_beneficiary_id',$applicant->id)->first();
        
        if(isset($beneficiary_avdisor->id))
            $advisor= QuotaAidAdvisor::find($beneficiary_avdisor->ret_fun_advisor_id);
        else
            $advisor = new QuotaAidAdvisor();

        
        $beneficiary_guardian = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id',$applicant->id)->first();
        
        if(isset($beneficiary_guardian->id))
            $guardian = QuotaAidLegalGuardian::find($beneficiary_guardian->quota_aid_legal_guardian_id);
        else
            $guardian = new QuotaAidLegalGuardian();            
        $procedures_modalities_ids = ProcedureModality::join('procedure_types','procedure_types.id','=','procedure_modalities.procedure_type_id')->where('procedure_types.module_id','=',3)->get()->pluck('id'); //3 por el module 3 de fondo de retiro        
        //return $procedures_modalities_ids;
        $procedures_modalities = ProcedureModality::whereIn('procedure_type_id',$procedures_modalities_ids)->get();        
        $file_modalities = ProcedureModality::get();

        $requirements = ProcedureRequirement::where('procedure_modality_id',$quota_aid->procedure_modality_id)->get();
        
        $documents = QuotaAidSubmittedDocument::where('quota_aid_mortuary_id',$id)->orderBy('procedure_requirement_id','ASC')->get();        
        $cities = City::get();
        $kinships = Kinship::get();

        $cities_pluck = City::all()->pluck('first_shortened', 'id');
        $birth_cities = City::all()->pluck('name', 'id');

        //$states = RetFunState::get();
        

        //$ret_fun_records=RetFunRecord::where('ret_fun_id', $id)->orderBy('id','desc')->get();
        
        ///proof
        $user = User::find(Auth::user()->id);
        $procedure_types = ProcedureType::where('module_id', 4)->get();
        $procedure_requirements = ProcedureRequirement::
                                    select('procedure_requirements.id','procedure_documents.name as document','number','procedure_modality_id as modality_id')
                                    ->leftJoin('procedure_documents','procedure_requirements.procedure_document_id','=','procedure_documents.id')
                                    ->orderBy('procedure_requirements.procedure_modality_id','ASC')
                                    ->orderBy('procedure_requirements.number','ASC')
                                    ->get();
                                    
        $modalities = ProcedureModality::where('procedure_type_id','<=', '2')->select('id','name', 'procedure_type_id')->get();            
        
        $observation_types = ObservationType::where('module_id',4)->get();
        
        //selected documents
        $submitted = QuotaAidSubmittedDocument::
            select('quota_aid_submitted_documents.id','procedure_requirements.number','quota_aid_submitted_documents.procedure_requirement_id','quota_aid_submitted_documents.comment','quota_aid_submitted_documents.is_valid')
            ->leftJoin('procedure_requirements','ret_fun_submitted_documents.procedure_requirement_id','=','procedure_requirements.id')
            ->orderby('procedure_requirements.number','ASC')
            ->where('ret_fun_submitted_documents.retirement_fund_id',$id);
         return $submitted->get();
            // ->pluck('ret_fun_submitted_documents.procedure_requirement_id','procedure_requirements.number');
        /**for validate doc*/
        $rol = Util::getRol();
        $module = Role::find($rol->id)->module;
        $wf_current_state = WorkflowState::where('role_id', $rol->id)->where('module_id', '=', $module->id)->first();
        return $wf_current_state;
        $can_validate = $wf_current_state->id == $retirement_fund->wf_state_current_id;
        $can_cancel = ($retirement_fund->user_id == $user->id && $retirement_fund->inbox_state == true);

        //workflow record
        $workflow_records = WorkflowRecord::where('ret_fun_id', $id)->orderBy('created_at', 'desc')->get();

        $first_wf_state = RetFunRecord::whereRaw("message like '%creo el Tr%'")->first();
        if ($first_wf_state) {
            $re = '/(?<= usuario )(.*)(?= cr.* )/mi';
            $str = $first_wf_state->message;
            preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
            $user_name = $matches[0][0];
            $rol = User::where('username','=', $user_name)->first()->roles->first();
            $first_wf_state = WorkflowState::where('role_id', $rol->id)->first();
        }


        // dd($first_wf_state);

        $wf_states = WorkflowState::where('module_id', '=', $module->id)->where('sequence_number','>',($first_wf_state->sequence_number ?? 1))->orderBy('sequence_number')->get();

        $correlatives = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->get();
        $steps = [];
        $data = $retirement_fund->getReceptionSummary();
        $is_editable = "1";
        if(isset($retirement_fund->id))
            $is_editable = "0";
        //return $data;
        //return $correlatives;
        $data = [
            'retirement_fund' => $retirement_fund,
            'affiliate' =>  $affiliate,
            'beneficiaries' =>  $beneficiaries,
            'applicant' => $applicant,
            'advisor'  =>  $advisor,
            'legal_guardian'    =>  $guardian,
            'procedure_modalities' => $procedures_modalities,
            'file_modalities'   =>  $file_modalities,
            'documents' => $documents,
            'cities'    =>  $cities,
            'kinships'   =>  $kinships,
            'cities_pluck' => $cities_pluck,
            'birth_cities' => $birth_cities,
            'states'    =>  $states,
            'ret_fun_records' => $ret_fun_records,
            'requirements'  =>  $procedure_requirements,
            'user'  =>  $user,
            'procedure_types'   =>  $procedure_types,
            'modalities'    =>  $modalities,
            'observation_types' => $observation_types,
            'observations' => $retirement_fund->ret_fun_observations,
            'submitted' =>  $submitted->pluck('ret_fun_submitted_documents.procedure_requirement_id','procedure_requirements.number'),
            'submit_documents' => $submitted->get(),
            'can_validate' =>  $can_validate,
            'can_cancel' =>  $can_cancel,
            'workflow_records' =>  $workflow_records,
            'first_wf_state' =>  $first_wf_state,
            'wf_states' =>  $wf_states,
            'is_editable'  =>  $is_editable
        ];
        // return $data;

        return view('quota_aid.show',$data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return \Illuminate\Http\Response
     */
    public function edit(RetirementFund $retirementFund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RetirementFund $retirementFund)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return \Illuminate\Http\Response
     */   
    public function destroy(RetirementFund $retirementFund)
    {
        //
    }
    
    public function getAllRetFun(Request $request)
    {
        
//        $offset = $request->offset ?? 0;
//        $limit = $request->limit ?? 10;
//        $sort = $request->sort ?? 'id';
//        $order = $request->order ?? 'desc';          
//        $last_name = strtoupper($request->last_name) ?? '';
//        $first_name = strtoupper($request->first_name) ?? '';
//        $code = $request->code ?? '';
//        $modality = strtoupper($request->modality) ?? '';
//        
//
//        $total = RetirementFund::select('retirement_funds.id')
//                                ->leftJoin('affiliates','retirement_funds.id','=','affiliates.id')
//                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modality_id','=','procedure_modalities.id')
//                                ->leftJoin('workflows','retirement_funds.workflow_id','=','workflows.id')                               
//                                ->where('retirement_funds.code','LIKE',$code.'%')
//                                //->where('procedure_modalities.name','LIKE',$modality.'%')
//                                ->where('affiliates.first_name','LIKE',$first_name.'%')
//                                ->where('affiliates.last_name','LIKE',$last_name.'%')                                
//                                ->count();
//        
//                                
//        $ret_funds = RetirementFund::select('retirement_funds.id','affiliates.first_name as first_name','affiliates.last_name as last_name','procedure_modalities.name as modality','workflows.name as workflow','retirement_funds.code','retirement_funds.reception_date','retirement_funds.total')
//                                ->leftJoin('affiliates','retirement_funds.id','=','affiliates.id')
//                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modality_id','=','procedure_modalities.id')
//                                ->leftJoin('workflows','retirement_funds.workflow_id','=','workflows.id')                               
//                                ->where('affiliates.first_name','LIKE',$first_name.'%')
//                                //->where('procedure_modalities.name','LIKE',$modality.'%')
//                                ->where('affiliates.last_name','LIKE',$last_name.'%')
//                                ->where('retirement_funds.code','LIKE',$code.'%')
//                                ->skip($offset)
//                                ->take($limit)
//                                ->orderBy($sort,$order)
//                                ->get();
//        
//        
//        return response()->json(['ret_funds' => $ret_funds->toArray(),'total'=>$total]);
    }
    
    public function generateProcedure(Affiliate $affiliate){  
                        
        //return $affiliate;
        //$this->authorize('create',QuotaAidMortuary::class);
        $hierarchy = $affiliate->degree->hierarchy;
        $procedure_types = ProcedureType::where('id','3')->orWhere('id','4')->get();
        
        $affiliate = Affiliate::select('affiliates.id','identity_card','registration','first_name','second_name','last_name','mothers_last_name','degrees.name as degree','civil_status','affiliate_states.name as affiliate_state','degree_id')
                                ->leftJoin('degrees','affiliates.degree_id','=','degrees.id')
                                ->leftJoin('affiliate_states','affiliates.affiliate_state_id','=','affiliate_states.id')
                                ->find($affiliate->id);
        
        
        $procedure_requirements = ProcedureRequirement::
                                    select('procedure_requirements.id','procedure_documents.name as document','number','procedure_modality_id as modality_id')
                                    ->leftJoin('procedure_documents','procedure_requirements.procedure_document_id','=','procedure_documents.id')                                    
                                    ->orderBy('procedure_requirements.procedure_modality_id','ASC')
                                    ->orderBy('procedure_requirements.number','ASC')
                                    ->get();
        
        $spouse = Spouse::where('affiliate_id',$affiliate->id)->first();
        if(!isset($spouse->id))
            $spouse = new Spouse();
        $modalities = ProcedureModality::where('procedure_type_id','3')->orWhere('procedure_type_id','4')->select('id','procedure_type_id','name')->get();
        
        $kinships = Kinship::get();
        
        $cities = City::get();
        $degrees = Degree::all();
        $data = [
            'requirements' => $procedure_requirements,
            'modalities'    => $modalities,
            'affiliate'  => $affiliate,
            'kinships'  =>  $kinships,
            'cities'    =>  $cities,
            'degrees'    =>  $degrees,
            'ret'    =>  $cities,
            'spouse' =>  $spouse,
            'procedure_types'    =>  $procedure_types,            
            'hierarchy' => $hierarchy,
        ];        
        return view('quota_aid.create',$data);        
    }

//    public function destroy($id)
//    {
//        //
//    }

}
