<?php

namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\AidReimbursement;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Auth;
use Validator;


class AidReimbursementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $rules = [                       
            'reim_salary' =>  'numeric|min:0',
            'reim_rent' =>  'numeric|min:1',
            'reim_amount' =>  'numeric|min:1'
            ];            
        $validator = Validator::make($request->all(),$rules);        
        if($validator->fails()){
           return json_encode($validator->errors()); 
        }
         //*********END VALIDATOR************//
        
         //return $request->affiliate_id;
        $reim = new AidReimbursement();
        $reim->user_id = Auth::user()->id;
        $reim->affiliate_id = $request->affiliate_id;
        $reim->month_year = $request->year.'-'.$request->month.'-01';           
        
        if(!isset($request->rent))
            $reim->rent = 0;
        else
            $reim->rent = strip_tags($request->rent) ?? $reim->rent;
        $reim->quotable = $reim->rent;
        $reim->dignity_rent = 0;                               
        $reim->total = $request->total;        
        $reim->save();        
        return $reim;        
    }
    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function show(Contribution $contribution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function edit(Contribution $contribution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contribution $contribution)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contribution $contribution)
    {
        //
    }    
}
