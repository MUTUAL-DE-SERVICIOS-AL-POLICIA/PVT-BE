<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\Contribution\ContributionCommitment;
use Illuminate\Http\Request;

class ContributionCommitmentController extends Controller
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
     * @param  \Muserpol\ContributionRate  $contributionRate
     * @return \Illuminate\Http\Response
     */
    public function show(ContributionRate $contributionRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\ContributionRate  $contributionRate
     * @return \Illuminate\Http\Response
     */
    public function edit(ContributionRate $contributionRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request 
     * @param  \Muserpol\ContributionCommitment  $contributionCommitment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
         //*********START VALIDATOR************//
        $rules=[];
        $rules = [           
            'commitment_memorandum' => 'required',
            'commitment_date' => 'required|date_format:Y-m-d',
            'commitment_destination' => 'required',
            'commitment_type' => 'required'
            ];
        $array_messages = [
            'commitment_memorandum.required' => 'El numero de Memorandum es obligatorio',
            'commitment_date.required'  =>  'La fecha del memorandum es obligatoria',
            'commitment_date.date_format' => 'El formato de la fecha es incorrecto',
            'commitment_destination.required'  =>  'El destino es obligatorio', 
            'commitment_type' => 'El tipo de aporte es obligatorio'
        ];
        $messages=array_merge($messages, $array_messages);
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            Session::flash('flash', 'This is a message!'); 
            return response()->json($validator->errors(), 400);
        }
         //*********END VALIDATOR************//

        
        if($id == -1){
            $commitment = ContributionCommitment::find($request->id);
            $commitment->state = 'BAJA';
            $commitment->save();
            $commitment->delete();            
            return $commitment;
        }                
        
        if($request->id==0){
            
            $commitment = new ContributionCommitment();            
            $commitment->affiliate_id = $request->affiliate_id;
            $commitment->commitment_date = date('Y-m-d');            
        }
        else 
            $commitment = ContributionCommitment::find($id);
        
        $commitment->commitment_type = $request->commitment_type;
        $commitment->number = $request->number;
        $commitment->destination = $request->destination;
        $commitment->commision_date = $request->commision_date;
        $commitment->state = "ALTA";
        
        //$commitment->state = $request->state;        
        $commitment->save();
        return $commitment;        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\ContributionRate  $contributionRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContributionRate $contributionRate)
    {
        //
    }
}
