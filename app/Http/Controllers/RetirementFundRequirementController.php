<?php

namespace Muserpol\Http\Controllers;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\RetirementFundRequirement;
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
        //  
    }
    public function retFun(Affiliate $affiliate){
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
        return view('ret_fun.step1_requirements',$data);        
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
        return 0;
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
