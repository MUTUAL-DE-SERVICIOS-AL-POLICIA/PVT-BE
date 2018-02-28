<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\RetirementFund\RetFunProcedure;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function settings(){
        $ret_fun_procedure = RetFunProcedure::where('is_enabled','true')->first();
        
        $data = [
            'ret_fun_procedure' =>  $ret_fun_procedure
        ];
        return view('home.settings',$data);
    }
}
