<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\Contribution\ContributionCommitment;
use Muserpol\Models\Affiliate;
use Illuminate\Http\Request;

use Validator;
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
     * @param  \Muserpol\ContributionCommitment  $contributtionCommitment
     * @return \Illuminate\Http\Response
     */
    public function show(ContributionCommitment $contributtionCommitment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\ContributionCommitment  $contributtionCommitment
     * @return \Illuminate\Http\Response
     */
    public function edit(ContributionCommitment $contributtionCommitment)
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
         $rules = [           
            'number' => 'required|numeric',
            'commision_date' => 'required|date',
            'destination' => 'required', 
            'commitment_type' => 'required' 
            ];
          $messages = [
            'number.required' => __('validation.memorandum'),
            // 'commision_date.required'  =>  'La fecha del memorandum es obligatoria',
            // 'commision_date.date' => 'El formato de la fecha es incorrecto',
            // 'destination.required'  =>  'El destino es obligatorio',  
            // 'commitment_type.required' => 'El tipo de aporte es obligatorio'
        ];  
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return response()->json($validator->errors(), 406);
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
            $commitment = ContributionCommitment::find($request->id);
        
        //return $request->id;
        $commitment->commitment_type = $request->commitment_type;
        
        $commitment->number = $request->number;
        $commitment->destination = $request->destination;
        $commitment->commision_date = $request->commision_date;
        $commitment->state = "ALTA";
        
        //$commitment->state = $request->state;        
        $commitment->save();
        ///'COMISION', 'BAJA TEMPORAL','AGREGADO POLICIAL'
        $affiliate = Affiliate::find($commitment->affiliate_id);
        if($commitment->commitment_type == 'COMISION')
            $affiliate->affiliate_state_id = 2;
        if($commitment->commitment_type == 'BAJA TEMPORAL')
            $affiliate->affiliate_state_id = 9;
        if($commitment->commitment_type == 'AGREGADO POLICIAL')
            $affiliate->affiliate_state_id = 10;
        $affiliate->save();
        return $commitment;     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\ContributionCommitment  $contributtionCommitment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContributionCommitment $contributtionCommitment)
    {
        //
    }
}
