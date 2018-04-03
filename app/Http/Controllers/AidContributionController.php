<?php
namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\AidContribution;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Carbon\Carbon;
use Yajra\Datatables\DataTables;
use Ixudra\Curl\Facades\Curl;
use Muserpol\Models\User;
use Validator;
use Log;
use Muserpol\Models\Voucher;
use Muserpol\Helpers\Util;
use Auth;
use Muserpol\Models\Contribution\AidCommitment;
class AidContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */            
    public function index()
    {        
        return 0;
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
     * Display the specified resource.
     *use Muserpol\Models\AffiliateState;
     * @param  \Muserpol\AidContribution  $aid_contribution
     * @return \Illuminate\Http\Response
     */
    public function show(Affiliate $affiliate)
    {

    }
    public function aidContributions($affiliate_id)
    {

        $affiliate = Affiliate::find($affiliate_id);
         $list = self::getMonthContributions($affiliate->id);
         
        $data = [
            'affiliate'=>$affiliate, 
            'list' => $list
        ];
        return view ('contribution.aid_contribution', $data);
    }
    public function getAllContributionsAid (DataTables $datatables, $affiliate_id)
    //Muestra todos los aportes de auxilio mortuorio del aportante
    {
        $affiliate = Affiliate::find($affiliate_id);
        $aid_contributions = $affiliate->aid_contributions;
        return $datatables->of($aid_contributions)
                        ->addIndexColumn()
                        ->addColumn('year', function($aid_contribution)
                        {
                            return Carbon::parse($aid_contribution->month_year)->year;
                        })
                        ->addColumn('month', function($aid_contribution)
                        {
                            return Carbon::parse($aid_contribution->month_year)->month;
                        })
                          ->make(true);

        $year = Carbon::parse($aid_contributions->month_year)->year;
        $month = Carbon::parse($aid_contributions->month_year)->month;
        $type = $aid_contributions->type;
        $quotable = $aid_contributions->quotable;
        $rent = $aid_contributions->rent;
        $dignityRent = $aid_contributions->dignityRent;
        $total = $aid_contributions->total;
        $data = [
            'affiliate' =>  $affiliate,
            'year' =>  $year,
            'month' => $month,
            'type' => $type,
            'quotable' => $quotable,
            'rent' => $rent,
            'dignityRent' => $dignityRent,
            'total' => $total,
            //'aid_contribution' => $aid_contribution
        ];
        return view ('contribution.aid_contribution', $data);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\AidContribution  $aid_contribution
     * @return \Illuminate\Http\Response
     */
    public function getMonthContributions($id)
    //Muestran los meses que faltan pagarse. Maximo 3 por reglamento
    {   
        $contributions=[];
        $lastMonths = AidContribution::where('affiliate_id', $id)
            ->orderBy('month_year', 'desc')
            ->first();
        if ($lastMonths) {
            $now = Carbon::now();
            $arrayDat = explode('-', $lastMonths->month_year);
            $lastMonths = Carbon::create($arrayDat[0], $arrayDat[1], $arrayDat[2]);
            $diff = $now->subMonths(1)->diffInMonths($lastMonths);
            $contribution = array();
            if ($diff > 2) {
                $month1 = Carbon::now()->subMonths(1);
                $month2 = Carbon::now()->subMonths(2);
                $month3 = Carbon::now()->subMonths(3);
                $contribution1 = array('year' => $month1->format('Y'), 'month' => $month1->format('m'),'monthyear' => $month1->format('m-Y'), 'sueldo' => 0, 'auxilioMortuorio' => 0, 'dignityRent' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
                $contribution2 = array('year' => $month2->format('Y'), 'month' => $month2->format('m'),'monthyear' => $month2->format('m-Y'), 'sueldo' => 0, 'auxilioMortuorio' => 0, 'dignityRent' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
                $contribution3 = array('year' => $month3->format('Y'), 'month' => $month3->format('m'),'monthyear' => $month3->format('m-Y'), 'sueldo' => 0, 'auxilioMortuorio' => 0, 'dignityRent' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
                $contributions = array($contribution3, $contribution2, $contribution1);
            } 
            else 
            {
                for ($i = 0; $i < $diff; $i++) {
                    $month_diff = Carbon::now()->subMonths($i + 1);
                    $month = explode('-', $month_diff);
                    $montyear = $month_diff->format('m-Y');
                    $contribution = array(
                        'year' => $month[0], 
                        'month' => $month[1], 
                        'monthyear' => $montyear, 
                        'sueldo' => 0, 
                        'auxilioMortuorio' => 0,
                        'dignityRent' => 0,
                        'interes' => 0, 
                        'subtotal' => 0
                        );
                    $contributions[$i] = $contribution;
                }
                $contributions = array_reverse($contributions);
            }
        }
        return $contributions;
    }

     public function edit(Contribution $contribution)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\AidContribution  $aid_contribution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contribution $contribution)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\AidContribution  $aid_contribution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contribution $contribution)
    {
        //
    }
    
    public function getAffiliateContributions(Affiliate $affiliate = null)
    {                
        //codigo para obtener totales para el resument
        //$this->authorize('update',new Contribution);
        $contributions = AidContribution::where('affiliate_id', $affiliate->id)->orderBy('month_year', 'DESC')->get();
        $group = [];        
        $aid = 0;        
        foreach ($contributions as $contribution) {
            $group[$contribution->month_year] = $contribution;
            $aid = $contribution->total + $aid;
        }
        $total = $aid;
        $dateentry = Util::getStringDate($affiliate->date_derelict);     
        $dateentry = "2017-01-01";
        $end = explode('-', $dateentry);
        $newcontributions = [];
        $month_end = $end[1];
        $year_end = $end[0];
        $month_start = (date('m') - 1);
        $year_start = date('Y');        
        $summary = array(            
            'aid' => $aid,
            'total' => $total,
            'dateentry' => $dateentry
        );
        $cities = City::all()->pluck('first_shortened', 'id');
        $birth_cities = City::all()->pluck('name', 'id');
        //get Commitment data
//        $commitment = ContributionCommitment::where('affiliate_id',$affiliate->id)->where('state','ALTA')->first();        
//        if(!isset($commitment->id))
//        {
//            $commitment = new ContributionCommitment();
//            $commitment->id = 0;
//            $commitment->affiliate_id = $affiliate->id;
//        }

        $data = [
            'contributions' => $group,            
            'affiliate_id' => $affiliate->id,            
            'year_start' => $year_start,
            'year_end' => $year_end,
            'summary' => $summary,
            'affiliate' => $affiliate,
            'cities' => $cities,
            'birth_cities' => $birth_cities,
            'commitment'    =>  $commitment,
            'today_date' =>  date('Y-m-d'),
            ];
        
        //return  date('Y-m-d');
         return view('contribution.affiliate_aid_contributions_edit', $data);
    }
    
    public function storeContributions(Request $request)
    {                
        //*********START VALIDATOR************//
        $rules=[];
        $messages=[];
        $input_data = $request->all();
        if(!empty($request->iterator))
        { 
            foreach ($request->iterator as $key => $iterator) 
            {              
                $input_data['rent'][$key]= strip_tags($request->rent[$key]);
                $input_data['dignityRent'][$key]= strip_tags($request->dignityRent[$key]);
                $input_data['total'][$key]= strip_tags($request->total[$key]);
                $array_rules = [                       
                    'rent.'.$key =>  'required|numeric',
                    'dignityRent.'.$key =>  'required|numeric|min:1',
                    'total.'.$key =>  'required|numeric|min:1'
                ];
                $rules=array_merge($rules,$array_rules);
            }
            $validator = Validator::make($input_data,$rules);
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
         //*********END VALIDATOR************//

        //$this->authorize('update',new Contribution);
        foreach ($request->iterator as $key => $iterator) {
            $contribution = AidContribution::where('affiliate_id', $request->affiliate_id)->where('month_year', $key)->first();
            if (isset($contribution->id)) {
                $contribution->total = strip_tags($request->total[$key]) ?? $contribution->total;
                $contribution->rent = strip_tags($request->rent[$key]) ?? $contribution->rent;
                $contribution->dignityRent = strip_tags($request->dignityRent[$key]) ?? $contribution->dignityRent;
                $contribution->save();
            } else {
                $contribution = new AidContribution();
                $contribution->user_id = Auth::user()->id;
                $contribution->affiliate_id = $request->affiliate_id;
                $contribution->rent = strip_tags($request->rent[$key]) ?? 0;
                $contribution->month_year = $key;
                $contribution->dignityRent = strip_tags($request->dignityRent[$key]) ?? 0;
                $contribution->total = strip_tags($request->total[$key]) ?? 0;
                $contribution->quotable = $contribution->rent-$contribution->dinity_rent;
                $contribution->type = 'PLANILLA';
                $contribution->save();
            }
        }
        return $contribution;
        }
    }

    public function getInterest(Request $request)
    {
        //Obtiene el interes a partir del subsiguiente mes que debe pagar. Ej. de enero corre el interes desde marzo
        $dateStart = Carbon::createFromDate($request->con['year'], $request->con['month'], '01')->addMonths(2)->format('d/m/Y');
        $dateEnd = Carbon::parse(Carbon::now()->toDateString())->format('d/m/Y');
        $mount=($request->con['sueldo']-$request->con['dignityRent'])*0.0203;
        $uri = 'https://www.bcb.gob.bo/calculadora-ufv/frmCargaValores.php?txtFecha=' . $dateStart . '&txtFechaFin=' . $dateEnd . '&txtMonto=' . $mount . '&txtCalcula=2';
        $foo = file_get_contents($uri);
        //return $foo;
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $json = '';
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //if( ($json = curl_exec($ch) ) === false)
        //return $foo;
        if( $foo === false)
        {
            Log::info("Error ".$httpcode ." ".$foo);
            return response('error', 500);
        }
        else
        {
            Log::info("Success: ".$httpcode. " ".$foo );
                return $foo;
        }
    }


    public function storeDirectContribution(Request $request)
    //Metodo para agregar contribuciones voluntarias o directas
    {
        // Se guarda voucher fecha, total 1 reg

        $voucher_code = Voucher::select('id', 'code')->orderby('id', 'desc')->first();
        if (!isset($voucher_code->id))
            $code = Util::getNextCode(""); 
        else
            $code = Util::getNextCode($voucher_code->code);
        //return $request->total."<<<<<<";
        $voucher = new Voucher();
        $voucher->user_id = Auth::user()->id;
        $voucher->affiliate_id = $request->afid;
        $voucher->voucher_type_id = 1;//$request->tipo; 1 default as Pago de aporte directo
        $voucher->total = $request->total;
       $voucher->payment_date = Carbon::now();
        $voucher->code = $code;
        $voucher->save();
      $result = [];      
        foreach ($request->aportes as $ap)  // guardar 1 a 3 reg en contribuciones
        {            
            $aporte=(object)$ap;
            $affiliate = Affiliate::find($request->afid);
            $aid_contribution = new AidContribution();
            $aid_contribution->user_id = Auth::user()->id;
            $aid_contribution->affiliate_id = $affiliate->id;            
            $aid_contribution->month_year = Carbon::createFromDate($aporte->year, $aporte->month,1);
            $aid_contribution->type='DIRECTO';
            $aid_contribution->quotable = $aporte->auxilioMortuorio;
            $aid_contribution->dignity_rent = $aporte->dignityRent;
            $aid_contribution->rent = $aporte->sueldo;
            $aid_contribution->total = $aporte->subtotal;
            $aid_contribution->interest = $aporte->interes;
            $aid_contribution->save();
            array_push($result, [
                'total'=>$aid_contribution->total,
                'month_year'=>$aporte->year.'-'.$aporte->month.'-01',
                    ]);
        }
        $data = [
            'aidcontribution'  =>  $result,
            'voucher_id'    => $voucher->id,
            'affiliate_id'  =>  $affiliate->id,
        ];
        return $data;
    }
}