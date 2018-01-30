<?php

namespace Muserpol\Http\Controllers;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\RetirementFundRequirement;
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
    public function retFun(){
        $procedure_requirements = ProcedureRequirement::
                                    select('procedure_requirements.id','procedure_documents.name as procedure_document','number','procedure_modalities.name as procedure_modality')
                                    ->leftJoin('procedure_documents','procedure_requirements.procedure_document_id','=','procedure_documents.id')
                                    ->leftJoin('procedure_modalities','procedure_requirements.procedure_modality_id','=','procedure_modalities.id')
                                    ->orderBy('procedure_requirements.procedure_modality_id','ASC')
                                    ->orderBy('procedure_requirements.number','ASC')
                                    ->get();
        //return$procedure_requirements;
        return view('ret_fun.step1_requirements');        
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
        //
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
