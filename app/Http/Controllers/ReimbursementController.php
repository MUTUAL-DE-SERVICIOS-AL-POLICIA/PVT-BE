<?php

namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\Reimbursement;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Category;
use Muserpol\Models\Contribution\Contribution;
use Auth;
use Log;
use Validator;
use Muserpol\Helpers\Util;


class ReimbursementController extends Controller
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
            'reim_salary' =>  'numeric|min:2000',
            'reim_gain' =>  'numeric|min:1',
            'reim_amount' =>  'numeric|min:1'
            ];
            //return $rules;
       /*  $messages = [
            'reim_salary.numeric' => 'El campo "Sueldo" debe ser numerico',
            'reim_salary.min'  =>  'El salario minimo es 2000',
            'reim_gain.numeric' => 'El campo "Total Ganado" debe ser numerico',
            'reim_gain.min'  =>  'La cantidad ganada debe ser mayor a 0', 
            'reim_amount.numeric' => 'El valor del Aporte debe ser numerico',
            'reim_amount.min'  =>  'El aporte debe ser mayor a 0'
        ]; */
        $validator = Validator::make($request->all(),$rules);        
       if($validator->fails()){
           return json_encode($validator->errors()); 
       }
         //*********END VALIDATOR************//

        //$category = Category::find($request->category);    
         //return $request->affiliate_id;
        $reim = new Reimbursement();
        $reim->user_id = Auth::user()->id;
        $reim->affiliate_id = $request->affiliate_id;
        $reim->month_year = $request->year.'-'.$request->month.'-01';
        $reim->type = "Planilla";        
        
        if(!isset($request->base_wage))
            $reim->base_wage = 0;
        else
            $reim->base_wage = strip_tags($request->base_wage) ?? $reim->base_wage;

        $reim->base_wage = $request->salary;        
        $reim->seniority_bonus = strip_tags($request->seniority_bonus) ?? 0;
        $reim->study_bonus = 0;
        $reim->position_bonus = 0;
        $reim->border_bonus = 0;
        $reim->east_bonus = 0;
        $reim->public_security_bonus = 0;
        $reim->gain = $request->gain;
        $reim->payable_liquid = 0;
        $reim->quotable = $request->quotable ?? 0;
        $reim->retirement_fund = $request->retirement_fund ?? 0;
        $reim->mortuary_quota = $request->mortuary_quota ?? 0;        
        $reim->total = $request->total ?? 0;
        $reim->subtotal = 0;        
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
    public function destroy(Request $request)
    {
        $reimbursement = Reimbursement::where('affiliate_id',$request->affiliate_id)
                                        ->where('month_year',$request->year.'-'.$request->month.'-01')
                                        ->first();
        if($reimbursement) {
            //$reimbursement->delete();
            $reimbursement->forceDelete();
            $data = [
                'month_year' => $request->year.'-'.$request->month.'-01',
            ];
            return $data;    
        }
        return 1;
    }    

    /**
     * Calculates reimbursement amount.
     * 
     * @param \Muserpol\Affiliate $affiliate
     * @param double $amount
     * @param int   $month
     * @return Object obj
     */
    public function caculateContribution(Affiliate $affiliate = null, $amount = 0, $month= 0){
        $date_end = date('Y')."-".$month."-01";
        $date_start = date('Y')."-01-01";
        $contributions = Contribution::where('affiliate_id',$affiliate->id)->whereDate('month_year','>=',$date_start)->whereDate('month_year','<',$date_end)->orderBy('month_year')->pluck('month_year');
        $number = $contributions->count();
        $quotable = Util::parseMoney($amount)*$number/($month-1);
                
        $data = [
            'quotable'  =>  $quotable,
            'number'    =>  $number,
            'contributions' =>  $contributions
        ];
        return $data;        
    }
}
