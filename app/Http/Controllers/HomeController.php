<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Muserpol\Models\RetirementFund\RetirementFund;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $procedure_modalities = RetirementFund::leftJoin('procedure_modalities', 'retirement_funds.procedure_modality_id', '=', 'procedure_modalities.id')
                                ->where('retirement_funds.code', 'not like', '%A%')
                                ->select(DB::raw('count(*) as quantity, procedure_modalities.name'))
                                ->groupBy('procedure_modalities.name')
                                ->get();

        $wf_states = RetirementFund::leftJoin('wf_states', 'retirement_funds.wf_state_current_id', '=', 'wf_states.id')
                                ->where('retirement_funds.code', 'not like', '%A%')
                                ->select(DB::raw("sum(case when retirement_funds.inbox_state = true then 1 else 0 end) as validados, sum(case when retirement_funds.inbox_state = false then 1 else 0 end) as pendientes, wf_states.first_shortened as name, wf_states.sequence_number"))
                                ->groupBy('wf_states.first_shortened', 'wf_states.sequence_number')
                                ->orderBy('wf_states.sequence_number')
                                ->get();
        $data = [
            'procedure_modalities' => $procedure_modalities,
            'wf_states' => $wf_states,
        ];
        return view('home', $data);
    }
    
    public function retFunSettings(){
        $ret_fun_procedure = RetFunProcedure::where('is_enabled','true')->first();
        
        $data = [
            'ret_fun_procedure' =>  $ret_fun_procedure
        ];
        return view('home.settings',$data);
    }
    public function lechuz()
    {
        return ' ';
    }
}
