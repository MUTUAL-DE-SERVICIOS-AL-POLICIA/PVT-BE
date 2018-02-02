<?php

namespace Muserpol\Http\Controllers;
use Validator;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\RetirementFundRequirement;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\Affiliate;
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
        $retirement_found = new RetirementFund();
        $retirement_found->user_id = null;
        $retirement_found->affiliate_id = null;
        $retirement_found->procedure_modaliy_id = null;
        $retirement_found->ret_fun_procedure_id = null;
        $retirement_found->city_start_id = null;
        $retirement_found->city_end_id = null;
        $retirement_found->code = null;
        $retirement_found->type = null;
        $retirement_found->subtotal = null;
        $retirement_found->total = null;
        $retirement_found->save();
        return 12;
        //return view('ret_fun.index',$data);
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
        //return $request->all();
        for($i=0;$i<100;$i++)
        {
            if($request->input('document'.$i) == 'registered')
                echo "<br>".$i;
            //echo $request->input('document'.$i)." ".$i."-<br>";
        }    
        
        $type = $request->input('account');
        
        
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
