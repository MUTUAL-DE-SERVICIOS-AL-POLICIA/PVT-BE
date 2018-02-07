<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\Kinship;
use Muserpol\Models\City;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunSubmittedDocument;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Muserpol\Models\RetirementFund\AddressRetFunBeneficiary;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Auth;
use Validator;
use Muserpol\Models\Address;
use Muserpol\Models\Spouse;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Muserpol\Models\RetirementFund\RetFunAdvisorBeneficiary;
use Muserpol\Models\RetirementFund\RetFunBeneficiaryLegalGuardian;

class RetirementFundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
          
        return view('ret_fun.index');
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $requirements = ProcedureRequirement::select('id')->get();        
        
        $procedure = \Muserpol\Models\RetirementFund\RetFunProcedure::where('is_enabled',true)->select('id')->first();
        
        
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
        
        
        $ret_fund  = RetirementFund::select('id','code')->orderby('id','desc')->first();
        if(!isset($ret_fund->id))
            $code=$this->getNextCode ("");
        else        
            $code=$this->getNextCode ($ret_fund->code);
        $retirement_found = new RetirementFund();
        $retirement_found->user_id = Auth::user()->id;
        $retirement_found->affiliate_id = $request->affiliate_id;
        $retirement_found->procedure_modalities_id = $request->ret_fun_modality;
        $retirement_found->ret_fun_procedure_id = $procedure->id;
        $retirement_found->city_start_id = Auth::user()->city_id;
        $retirement_found->city_end_id = Auth::user()->city_id;
        $retirement_found->code = $code;
        $retirement_found->workflow_id = 4;
        $retirement_found->wf_state_current_id = 1;
        //$retirement_found->type = "Pago"; default value
        $retirement_found->subtotal = 0;
        $retirement_found->total = 0;
        $retirement_found->save();                       
        
        foreach ($requirements  as  $requirement)
        {
            if($request->input('document'.$requirement->id) == 'checked')
            {
                $submit = new RetFunSubmittedDocument();
                $submit->retirement_fund_id = $retirement_found->id;
                $submit->procedure_requirement_id = $requirement->id;
                $submit->reception_date = date('Y-m-d');
                $submit->comment = $request->input('comment'.$requirement->id);                
                $submit->save();
            }                
        }
        $account_type = $request->input('accountType');    

        $beneficiary = new RetFunBeneficiary();
        $beneficiary->retirement_fund_id = $retirement_found->id;
        $beneficiary->city_identity_card_id = $request->applicant_city_identity_card;
        $beneficiary->kinship_id = null;
        $beneficiary->identity_card = $request->applicant_identity_card;
        $beneficiary->last_name = $request->applicant_last_name;
        $beneficiary->mothers_last_name = $request->applicant_mothers_last_name;
        $beneficiary->first_name = $request->applicant_first_name;
        $beneficiary->second_name = $request->applicant_second_name;
        $beneficiary->surname_husband = $request->applicant_surname_husband;        
        $beneficiary->gender = "M";        
        $beneficiary->phone_number = $request->applicant_phone_number;
        $beneficiary->cell_phone_number = $request->applicant_cell_phone_number;        
        $beneficiary->type = "S";
        $beneficiary->save();
                
        if($account_type == '2')
        {
            $advisor = new RetFunAdvisor();
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
            $advisor->phone_number = $request->applicant_phone_number;
            $advisor->cell_phone_number = $request->applicant_cell_phone_number;        
            $advisor->name_court = $request->advisor_name_court;            
            $advisor->resolution_number = $request->advisor_resolution_number;
            $advisor->resolution_date = $request->advisor_resolution_date;
            $advisor->type = "Natural";
            $advisor->save();
            
            $advisor_beneficiary = new RetFunAdvisorBeneficiary();
            $advisor_beneficiary->ret_fun_beneficiary_id = $beneficiary->id;
            $advisor_beneficiary->ret_fun_advisor_id = $advisor->id;
            $advisor->save();
        }
        
        if($account_type == '3')
        {
            $legal_guardian = new RetFunLegalGuardian();
            $legal_guardian->retirement_fund_id = $retirement_found->id;
            $legal_guardian->city_identity_card_id = $request->applicant_city_identity_card;            
            $legal_guardian->identity_card = $request->applicant_identity_card  ;
            $legal_guardian->last_name = $request->applicant_last_name;
            $legal_guardian->mothers_last_name = $request->applicant_mothers_last_name;
            $legal_guardian->first_name = $request->applicant_first_name;
            $legal_guardian->second_name = $request->applicant_second_name;
            $legal_guardian->surname_husband = $request->applicant_surname_husband;        
            //$legal_guardian->gender = "M";                    
            $legal_guardian->phone_number = $request->applicant_phone_number;
            $legal_guardian->cell_phone_number = $request->applicant_cell_phone_number;        
            $legal_guardian->number_authority = $request->legal_guardian_number_authority;            
            $legal_guardian->notary_of_public_faith = $request->legal_guardian_notary_of_public_faith;
            $legal_guardian->notary = $request->legal_guardian_notary;
            $legal_guardian->save();
            
            $beneficiary_legal_guardian = new RetFunBeneficiaryLegalGuardian();
            $beneficiary_legal_guardian->ret_fun_beneficiary_id = $beneficiary->id;
            $beneficiary_legal_guardian->ret_fun_legal_guardian_id = $legal_guardian->id;
            $beneficiary_legal_guardian->save();
            //$beneficiary->type = "N";            
        }
        
        
        $address = new Address();
        $address->city_address_id = 1;
        $address->zone = $request->beneficiary_zone;
        $address->street = $request->beneficiary_street;
        $address->number_address = $request->beneficiary_number_address;
        $address->save();
        
        $address_rel = new AddressRetFunBeneficiary();
        $address_rel->ret_fun_beneficiary_id = $beneficiary->id;
        $address_rel->address_id = $address->id;
        $address_rel->save();
        
        $first_name = $request->beneficiary_first_name;
        $second_name = $request->beneficiary_second_name;
        $last_name = $request->beneficiary_last_name;
        $mothers_last_name = $request->beneficiary_mothers_last_name;
        $surname_husband = $request->surname_husband;
        $identity_card = $request->beneficiary_identity_card;
        $city_id = $request-> beneficiary_city_identity_card;
        $birth_date = $request->beneficiary_birth_date;
        $kinship = $request->beneficiary_kinship;
        for($i=0;$i<sizeof($first_name);$i++){
            if($first_name[$i] != "" && $last_name[$i] != ""){
                $beneficiary = new RetFunBeneficiary();
                $beneficiary->retirement_fund_id = $retirement_found->id;
                $beneficiary->city_identity_card_id = $city_id[$i];
                $beneficiary->kinship_id = $kinship[$i];
                $beneficiary->identity_card = $identity_card[$i];
                $beneficiary->last_name = $last_name[$i];
                $beneficiary->mothers_last_name = $mothers_last_name[$i];
                $beneficiary->first_name = $first_name[$i];
                $beneficiary->second_name = $second_name[$i];
                $beneficiary->surname_husband = $surname_husband[$i];                
                $beneficiary->birth_date = $birth_date[$i];
                $beneficiary->gender = "M";
                //$beneficiary->civil_status = $request->
                //$beneficiary->phone_number = $request->;
                //$beneficiary->cell_phone_number = $request->;               
                $beneficiary->type = "N";
                $beneficiary->save();                
            }        
        }
        
        
        $data = [
            
        ];
        
        return view('ret_fun.show',$data);        
        
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
        $retirement_fund = RetirementFund::find($id);
        
        $affiliate = Affiliate::find($retirement_fund->affiliate_id);
        
        $beneficiaries = RetFunBeneficiary::find($retirement_fund->beneficiary_id);
        //$
        $advisor = RetFunAdvisor::
        //$tuto
        
        $data = [
            'retirement_fund' => $retirement_fund,
            'affiliate' =>  $affiliate,
            'beneficiaries' =>  $beneficiaries,
            
        ];
        return $data;
        return view('ret_fun.show',$data);
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
        
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';          
        $last_name = strtoupper($request->last_name) ?? '';
        $first_name = strtoupper($request->first_name) ?? '';
        $code = $request->code ?? '';
        $modality = strtoupper($request->modality) ?? '';
        

        $total = RetirementFund::select('retirement_funds.id')
                                ->leftJoin('affiliates','retirement_funds.id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modalities_id','=','procedure_modalities.id')
                                ->leftJoin('workflows','retirement_funds.workflow_id','=','workflows.id')                               
                                ->where('retirement_funds.code','LIKE',$code.'%')
                                //->where('procedure_modalities.name','LIKE',$modality.'%')
                                ->where('affiliates.first_name','LIKE',$first_name.'%')
                                ->where('affiliates.last_name','LIKE',$last_name.'%')                                
                                ->count();
        
                                
        $ret_funds = RetirementFund::select('retirement_funds.id','affiliates.first_name as first_name','affiliates.last_name as last_name','procedure_modalities.name as modality','workflows.name as workflow','retirement_funds.code','retirement_funds.reception_date','retirement_funds.total')
                                ->leftJoin('affiliates','retirement_funds.id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modalities_id','=','procedure_modalities.id')
                                ->leftJoin('workflows','retirement_funds.workflow_id','=','workflows.id')                               
                                ->where('affiliates.first_name','LIKE',$first_name.'%')
                                //->where('procedure_modalities.name','LIKE',$modality.'%')
                                ->where('affiliates.last_name','LIKE',$last_name.'%')
                                ->where('retirement_funds.code','LIKE',$code.'%')
                                ->skip($offset)
                                ->take($limit)
                                ->orderBy($sort,$order)
                                ->get();
        
        
        return response()->json(['ret_funds' => $ret_funds->toArray(),'total'=>$total]);
    }
    
    public function generateProcedure(Affiliate $affiliate){  
        
        $affiliate = Affiliate::select('affiliates.id','identity_card','registration','first_name','second_name','last_name','mothers_last_name','degrees.name as degree','civil_status','affiliate_states.name as affiliate_state')
                                ->leftJoin('degrees','affiliates.id','=','degrees.id')
                                ->leftJoin('affiliate_states','affiliates.affiliate_state_id','=','affiliate_states.id')
                                ->find($affiliate->id);
                 
        $procedure_requirements = ProcedureRequirement::
                                    select('procedure_requirements.id','procedure_documents.name as document','number','procedure_modality_id as modality_id')
                                    ->leftJoin('procedure_documents','procedure_requirements.procedure_document_id','=','procedure_documents.id')                                    
                                    ->orderBy('procedure_requirements.procedure_modality_id','ASC')
                                    ->orderBy('procedure_requirements.number','ASC')
                                    ->get();
        
        $spouse = Spouse::where('affiliate_id',$affiliate->id)->first();
        $modalities = ProcedureModality::where('procedure_type_id','2')->select('id','name')->get();
        
        $kinships = Kinship::get();
        
        $cities = City::get();
        
        $data = [
            'requirements' => $procedure_requirements,
            'modalities'    => $modalities,
            'affiliate'  => $affiliate,
            'kinships'  =>  $kinships,
            'cities'    =>  $cities,
            'ret'    =>  $cities,
            'spouse' =>  $spouse,
        ];
      
        return view('ret_fun.create',$data);        
    }
    private function getNextCode($actual){
        $year =  date('Y');
        if($actual == "")
            return "1/".$year;
        
        $data = explode('/', $actual);        
        if(!isset($data[1]))
            return "1/".$year;                
        return ($year!=$data[1]?"1":($data[0]+1))."/".$year;
    }
}
