<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\Contribution\Contribution;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\User;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use Auth;
use Validator;
class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInteres(Request $request)
    {        
        $dateStart = $request->txtFecha;
        $dateEnd = $request->txtFechaFin;
        $mount = $request->txtMonto;
        $foo =file_get_contents('https://www.bcb.gob.bo/calculadora-ufv/frmCargaValores.php?txtFecha='.$dateStart.'&txtFechaFin='.$dateEnd.'&txtMonto='.$mount.'&txtCalcula=2');
        return $foo;      
    }

    public function getMonth($id)
    {
        $lastMonths = Contribution::where('affiliate_id', $id)
        ->orderBy('month_year','desc')
        ->first(); 
        $now = Carbon::now();      
         $arrayDat = explode('-', $lastMonths->month_year);
         $lastMonths = Carbon::create($arrayDat[0], $arrayDat[1], $arrayDat[2]);
         $diff = $now->diffInMonths($lastMonths);  
         if($diff>2)
         {
            /* $month[0] = $now->subMonths(1)->format('m-Y');
            $month[1] = $now->subMonths(1)->format('m-Y');
            $month[2] = $now->subMonths(3)->format('m-Y');
             */ 
            $month1 = $now->subMonths(1)->format('m-Y');
            $month2 = $now->subMonths(1)->format('m-Y');
            $month3 = $now->subMonths(1)->format('m-Y');
            $month=array ('mes1' => $month1, 'mes2'=>$month2, 'mes3'=>$month3);
         }
         else
         {
             for ($i = 0; $i < $diff; $i++)
             { 
                $month[$i] = $now->subMonths(1)->format('m-Y');
             }
         }
         return $month;
    }
    
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
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request-all(),[]);
        $validator->after(function($validator){
            if(false)            
                $validator->errors()-add('Aporte', 'El aporte no puede ser realizado');
         });         
        if($validator->fails())
        {
            return $validator->errors();   
        }
        $affiliate = Affiliate::find($request->affiliate_id);
        $contribution = new Contribution();
        $contribution->user_id = Auth::user()->id;
        $contribution->affiliate_id = $affiliate->affiliate_id;
        $contribution->degree_id = $affiliate->degree_id;
        $contribution->unit_id = $affiliate->unit_id;
        $contribution->breakdown_id = $affiliate->breakdown_id;
        $contribution->category_id = $affiliate->category_id;
        $contribution->month_year = date('Y-m-d');       
        $contribution->gain = 0;
        $contribution->retirement_fund = 0;
        $contribution->mortuary_quota = 0;
        $contribution->total = 0;
        $contribution->interes = 0;
        $contribution->type='Directo';
        $contribution->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function show(Contribution $contribution)
    {
       return 'Cechus y Anitaaaaa!!';
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
    public function generateContribution(Affiliate $affiliate)
    {   
        return View('contribution.create',$affiliate);
    }
}
