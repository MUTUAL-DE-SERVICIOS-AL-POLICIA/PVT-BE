<?php

namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\Reimbursement;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Category;
use Auth;


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
        $category = Category::find($request->category);    
        //return $category;
        $reim = new Reimbursement();
        $reim->user_id = Auth::user()->id;
        $reim->affiliate_id = $request->affiliate_id;
        $reim->month_year = $request->year.'-'.$request->month.'-01';
        $reim->type = "Planilla";        
        $reim->base_wage = $request->salary;
        $reim->seniority_bonus = $category->percentage*$reim->base_wage;
        $reim->study_bonus = 0;
        $reim->position_bonus = 0;
        $reim->border_bonus = 0;
        $reim->east_bonus = 0;
        $reim->public_security_bonus = 0;
        $reim->gain = $request->gain;
        $reim->payable_liquid = 0;
        $reim->quotable = 0;
        $reim->retirement_fund = 0;
        $reim->mortuary_quota = 0;
        $reim->mortuary_aid = 0;
        $reim->total = $request->total;
        $reim->subtotal = 0;
        $reim->ipc = 0;
        $reim->months = $this->getRebursimentMonths($request->month);         
        $reim->save();        
        return json_encode($request->all());
    }
    private function getRebursimentMonths($month){
        $month_add = 1;
        $result  = "";
        while($month>$month_add){
            $result .= $month_add.",";
            $month_add++;
        }
        $result .= $month_add;
        return $result;
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
