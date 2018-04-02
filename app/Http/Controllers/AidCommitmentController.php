<?php

namespace Muserpol\Http\Controllers;

use Muserpol\AidCommitment;
use Illuminate\Http\Request;

class AidCommitmentController extends Controller
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
     * @param  \Muserpol\AidCommitment  $aidCommitment
     * @return \Illuminate\Http\Response
     */
    public function show(AidCommitment $aidCommitment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\AidCommitment  $aidCommitment
     * @return \Illuminate\Http\Response
     */
    public function edit(AidCommitment $aidCommitment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\AidCommitment  $aidCommitment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($id == -1){
            $aid_commitment = AidCommitment::find($request->id);
            $aid_commitment->state = 'BAJA';
            $aid_commitment->save();
            $aid_commitment->delete();            
            return $aid_commitment;
        }                
        
        if($request->id==0){            
            $aid_commitment = new AidCommitment();            
            $aid_commitment->affiliate_id = $request->affiliate_id;            
        }
        else 
            $aid_commitment = AidCommitment::find($request->id);                
            $aid_commitment->date_commitment = $request->date_commitment;
            $aid_commitment->contributor = $request->contributor;
            $aid_commitment->pension_declaration = $request->pension_declaration;
            $aid_commitment->pension_declaration_date = $request->pension_declaration_date;
            $aid_commitment->state = "ALTA";
                
        $aid_commitment->save();
        ///'TITULAR', 'ESPOSA','CONYUGE'
        $affiliate = Affiliate::find($aid_commitment->affiliate_id);
        if($aid_commitment->contributor == 'T')
            $affiliate->affiliate_state_id = 5;
        if($aid_commitment->contributor == 'E')
            $affiliate->affiliate_state_id = 5;
        if($aid_commitment->contributor == 'C')
            $affiliate->affiliate_state_id = 5;
        $affiliate->save();
        return $aid_commitment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\AidCommitment  $aidCommitment
     * @return \Illuminate\Http\Response
     */
    public function destroy(AidCommitment $aidCommitment)
    {
        //
    }
}
