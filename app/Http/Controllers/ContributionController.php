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
use DateTime;
use Muserpol\Helpers\Util;
class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInterest(Request $request)
    {
        $dateStart = '01/'.$request->con['month'].'/'.$request->con['year'];
        $dateEnd = Carbon::parse(Carbon::now()->toDateString())->format('d/m/Y');      
        $mount = $request->con['sueldo'];
        $uri = 'https://www.bcb.gob.bo/calculadora-ufv/frmCargaValores.php?txtFecha='.$dateStart.'&txtFechaFin='.$dateEnd.'&txtMonto='.$mount.'&txtCalcula=2';
        $foo =file_get_contents($uri);
        return $foo;      
    }

    public function getMonthContributions($id)
    {
        $lastMonths = Contribution::where('affiliate_id', $id)
        ->orderBy('month_year','desc')
        ->first(); 
        $now = Carbon::now();      
         $arrayDat = explode('-', $lastMonths->month_year);
         $lastMonths = Carbon::create($arrayDat[0], $arrayDat[1], $arrayDat[2]);
         $diff = $now->diffInMonths($lastMonths); 
         $contribution = array();
         if($diff>2)
         {  
            $month1 = Carbon::now()->subMonths(1);                      
            $month2 = Carbon::now()->subMonths(2);        
            $month3 = Carbon::now()->subMonths(3);
            //dd($month1.' '.$month2.' '.$month3);
            $contribution1 = array('year'=>$month1->format('Y'), 'month'=>$month1->format('m'), 'monthyear'=>$month1->format('m-Y'), 'sueldo'=>0, 'fr'=>0,'cm'=>0, 'interes'=>0, 'subtotal'=>0);
            $contribution2 = array('year'=>$month2->format('Y'), 'month'=>$month2->format('m'), 'monthyear'=>$month2->format('m-Y'), 'sueldo'=>0, 'fr'=>0,'cm'=>0,  'interes'=>0, 'subtotal'=>0);
            $contribution3 = array('year'=>$month3->format('Y'), 'month'=>$month3->format('m'), 'monthyear'=>$month3->format('m-Y'), 'sueldo'=>0, 'fr'=>0,'cm'=>0,  'interes'=>0, 'subtotal'=>0);
            $contributions = array($contribution1,$contribution2,$contribution3);
         }  
         else
         {
             for ($i = 0; $i < $diff; $i++)
             { 
                $contribution = array('year'=>$month[$i]->format('Y'), 'month'=>$month[$i]->format('m'), 'monthyear'=>$month[$i], 'sueldo'=>0, 'fr'=>0,'cm'=>0, 'interes'=>0, 'subtotal'=>0);
                $contributions.array_push($contribution);
             }
         }
         return $contributions;
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
    public function storeDirectContribution(Request $request)
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
     *use Muserpol\Models\AffiliateState;

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

    public function adicionalInfo(Affiliate $affiliate)
    {
        $contributions = Contribution::where('affiliate_id', $affiliate->id)->get();
        $fondoret = null;
        $quotaaid = null;
        foreach($contributions as $contribution){
            $fondoret = $contribution->retirement_fund + $fondoret;
            $quotaaid = $contribution->mortuary_quota + $quotaaid;
        }
        $total = $fondoret + $quotaaid;
        $dateentry = Util::getStringDate($affiliate->date_entry);
        //return $fondoret.'    '.$quotaaid.'    '.$total.'    '.$affiliate->date_entry;
        $data= array( 
            'fondoret' => $fondoret,
            'quotaaid' => $quotaaid,
            'total' => $total,
            'dateentry' => $dateentry
        );
        return view('contribution.aditional_info')->with($data);
    }
        
    public function generateContribution(Affiliate $affiliate)
    {   
        $contributions = self::getMonthContributions($affiliate->id);           
        return View('contribution.create',compact('affiliate', 'contributions'));
    }
}
