<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\Spouse;
use Muserpol\Models\City;
use Muserpol\Models\Affiliate;
use Illuminate\Http\Request;
use Log;
use Validator;
use Muserpol\Helpers\Util;
class SpouseController extends Controller
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

        //*********START VALIDATOR************//        
        // $rules=[];        
        // $has_commitment = false;
        // $datediff = 0;
        // $commitment = ContributionCommitment::where('affiliate_id',$request->afid)->where('state','ALTA')->first();
        // if(!isset($commitment->id))
        //     $has_commitment = true;
        // else
        // {
        //     $commision_date = strtotime($commitment->commision_date) ;
        //     $commtiment_date = strtotime($commitment->commitment_date);
        //     $datediff = $commtiment_date - $commision_date;
        //     $datediff = round($datediff / (60 * 60 * 24));
        // }
        
        // $biz_rules = [
        //     'has_commitment'    =>  $has_commitment?'required':'',
        //     'valid_commitment'  =>  $datediff>90?'required':''
        // ];            
                    
        // foreach ($request->aportes as $key => $ap)
        // {                                            
        //     $aporte=(object)$ap;
        //     $cont = Contribution::where('affiliate_id',$request->afid)->where('month_year',$aporte->year.'-'.$aporte->month.'-01')->first();
        //     $has_contribution = false;
        //     if(isset($cont->id))
        //         $has_contribution = true;
            
        //     $biz_rules = [
        //         'has_contribution.'.$key    =>  $has_contribution?'required':'',
        //     ];
            
        //     $rules=array_merge($rules,$biz_rules);
        //     //$aporte=(object)$ap;
        //     $array_rules = [
        //         'aportes.'.$key.'.sueldo' =>  'required|numeric|min:0',
        //         'aportes.'.$key.'.fr' =>  'required|numeric',
        //         'aportes.'.$key.'.cm' =>  'required|numeric',
        //         'aportes.'.$key.'.subtotal' =>  'required|numeric',
        //         'aportes.'.$key.'.interes' =>  'required|numeric',
        //         'aportes.'.$key.'.year' =>  'required|numeric|min:1700',
        //         'aportes.'.$key.'.month' =>  'required|numeric|min:1|max:12',
        //     ];
        //     $rules=array_merge($rules,$array_rules);
        // }
        
        // $rules = array_merge($rules,$biz_rules);
        // $validator = Validator::make($request->all(),$rules);
        // if($validator->fails()){            
        //     return response()->json($validator->errors(), 406);
        // }                
        
         //*********END VALIDATOR************//    


        $spouse = new Spouse();
        $spouse->user_id = Auth::user()->id;
        $spouse->affiliate_id = $request->affiliate_id;
        $spouse->city_identity_card_id = $request->city_identity_card_id;
        $spouse->identity_card = $request->identity_card;
        $spouse->registration = $request->registration;
        $spouse->last_name = $request->last_name;
        $spouse->mothers_last_name = $request->mother_last_namel;
        $spouse->first_name = $request->first_name;
        $spouse->second_name = $request->second_name;
        $spouse->surname_husband = $request->surname_husband;
        $spouse->civil_status = $request->civil_status;
        $spouse->birth_date = $request->birth_date;
        $spouse->date_death = $request->date_death;
        $spouse->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $affiliate_id)
    {
        $rules = [
            'identity_card' => 'required|min:1',
            'city_identity_card_id' => 'required|min:1',
            'first_name' => 'required|min:1',
            'last_name' => '',
            'mothers_last_name' => '',
            'birth_date' => 'required',
        ];
        $messages = [];
        if (!$request->last_name && !$request->mothers_last_name) {
            //only for flash message
            $rules['last_name'] .= 'required';
            $messages = [
                'last_name.required' => 'El campo Apellido Paterno o Materno es requerido.',
            ];
        }
        try {
            $validator = Validator::make($request->all(), $rules, $messages)->validate();
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 403);
        }
        Util::updateCreateSpousePersonalInfo($affiliate_id, $request);
        $spouse = Affiliate::find($affiliate_id)->spouse->first();
        if (!$spouse) {
            $spouse = new Spouse();
        } else {
            $spouse->load([
                'city_identity_card:id,first_shortened',
                'city_birth:id,name',
            ]);
        }
        $datos = array('spouse' => $spouse, 'city_birth' => $spouse->city_birth, 'city_identity_card' => $spouse->city_identity_card);
        return $datos;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
