<?php

namespace Muserpol\Http\Controllers;
use Validator;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\RetirementFundRequirement;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Muserpol\Models\RetirementFund\RetFunSubmittedDocument;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\Affiliate;
use Auth;
//use Muserpol\Models\RetirementFund;
use Illuminate\Http\Request;

class RetirementFundRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {                     
        
        $requirements = ProcedureRequirement::select('id')->get();
        
        
        $procedure = \Muserpol\Models\RetirementFund\RetFunProcedure::where('is_enabled',true)->select('id')->first();
                
        $retirement_found = new RetirementFund();
        $retirement_found->user_id = Auth::user()->id;
        $retirement_found->affiliate_id = $request->input('affiliate_id');
        $retirement_found->procedure_modaliy_id = $request->input('modality_id');
        $retirement_found->ret_fun_procedure_id = $procedure->id;
        $retirement_found->city_start_id = Auth::user()->city_id;
        $retirement_found->city_end_id = Auth::user()->city_id;
        $retirement_found->code = null;
        //$retirement_found->type = "Pago"; default value
        $retirement_found->subtotal = 0;
        $retirement_found->total = 0;
        
        for($i=0;$i<= sizeof($requirements);$i++)
        {
            if($request->input('document'.$i) == 'checked')
            {
                $submit = new RetFunSubmittedDocument();
                $submit->retirement_fund_id = $retirement_found->id;
                $submit->procedure_requirement_id = $requirements->id;
                $submit->reception_date = date('Y/m/d');
                $submit->comment = '';
                $submit->save();
            }                
        }
        
        
                
        
        $type = $request->input('account');     
        $applicant = new RetFunApplicant();
        $applicant->retirement_fund_id = null;
        $applicant->city_identity_card_id = null;
        $applicant->kinship_id = null;
        $applicant->identity_card = null;
        $applicant->last_name = null;
        $applicant->mothers_last_name = null;
        $applicant->first_name = null;
        $applicant->second_name = null;
        $applicant->surname_husband = null;
        $applicant->type = null;
        $applicant->number_authority = null;
        $applicant->notary_of_public_faith = null;
        $applicant->notary = null;
        $applicant->save();
        
        $address = new RetFunAddressApplicant();
        $address->user_id = null;
        $address->affiliate_id = null;
        $address->city_address_id = null;
        $address->zone = null;
        $address->street = null;
        $address->number_address= null;
        $address->save();
        
        $beneficiary = new RetFunBeneficiary();
        $beneficiary->retirement_fund_id = null;
        $beneficiary->city_identity_card_id = null;
        $beneficiary->kinship_id = null;
        $beneficiary->identity_card = null;
        $beneficiary->last_name = null;
        $beneficiary->mothers_last_name = null;
        $beneficiary->first_name = null;
        $beneficiary->second_name = null;
        $beneficiary->surname_husband = null;
        $beneficiary->birth_date = null;
        $beneficiary->gender = null;
        $beneficiary->civil_status = null;
        $beneficiary->phone_number = null;
        $beneficiary->cell_phone_number = null;
        $beneficiary->home_address = null;
        $beneficiary->work_address = null;
        $beneficiary->save();
        
        $advisor = new RetFunAdvisor();
        $advisor->city_identity_card = null;
        $advisor->kinship_id = null;
        $advisor->identity_card = null;
        $advisor->last_name = null;
        $advisor->mothers_last_name = null;
        $advisor->fisrt_name = null;
        $advisor->second_name = null;
        $advisor->surname_husband = null;
        $advisor->birth_day = null;
        $advisor->gender = null;
        $advisor->type = null;
        $advisor->name_court = null;
        $advisor->resolution_number = null;
        $advisor->resolution_date = null;
        $advisor->phone_number = null;
        $advisor->cell_phone_number = null;
        $advisor->save();
        
        $beneficiary_advisor = new RetFunAdvisorBeneficiary();
        $beneficiary_advisor->ret_fun_beneficiary_id = null;
        $beneficiary_advisor->ter_fun_advisor_id = null;
        $beneficiary_advisor->save();
        
        

        
//        $validator = Validator::make($request->all(), [
//            //'name' => 'required|max:5',            
//        ]);        
        $validator->after(function($validator){
            if(false)
                $validator->errors()->add('field', 'Something is wrong with this field!');
        });
        if($validator->fails()){
            return redirect()->route('ret_fun.index');
        }
        else{
            //$retirement_found->save();
            return redirect()->route('affiliate.index');
        }
                        
    }
    public function generateProcedure(Affiliate $affiliate){
//        $affiliate = Affiliate::select('id','')
//                        ->find($affiliate->id);
//        
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
//        $requirements = [];
//        foreach ($procedure_requirements as $require){
//            array_push($requirements, $require);      
//            $requirements->number2 = "1" ;
//        }
                
        $modalities = ProcedureModality::where('procedure_type_id','2')->select('id','name')->get();
        
        $data = [
            'requirements' => $procedure_requirements,
            'modalities'    => $modalities,
            'affiliate'  => $affiliate,
        ];       
        //return $data;
        return view('ret_fun.index',$data);        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ret_fun.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:5',            
        ]);        
        $validator->after(function($validator){
            if(false)
                $validator->errors()->add('field', 'Something is wrong with this field!');
        });
        if($validator->fails()){
            return redirect()->route('ret_fun.create');
        }
        else{
            //$retirement_found->save();
            return redirect()->route('affiliate.index');
        }
        
//        for($i=0;$i<100;$i++)
//        {
//            if($request->input('document'.$i) == 'registered')
//                echo "<br>".$i;            
//        }    
//        
        //$type = $request->input('account');        
        
//         $validator = Validator::make($request->all(), [
//            'name' => 'required|max:5',
//            'lastname' => 'required',
//        ]);
//        $validator->after(function($validator){
//            if(false)
//                $validator->errors()->add('field', 'Something is wrong with this field!');
//        });
//        if($validator->fails()){
//            return $validator->errors();
//        }
        return 213;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\RetirementFundRequirement  $retirementFundRequirement
     * @return \Illuminate\Http\Response
     */
    public function show(RetirementFundRequirement $retirementFundRequirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\RetirementFundRequirement  $retirementFundRequirement
     * @return \Illuminate\Http\Response
     */
    public function edit(RetirementFundRequirement $retirementFundRequirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\RetirementFundRequirement  $retirementFundRequirement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RetirementFundRequirement $retirementFundRequirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\RetirementFundRequirement  $retirementFundRequirement
     * @return \Illuminate\Http\Response
     */
    public function destroy(RetirementFundRequirement $retirementFundRequirement)
    {
        //
    }
}
