<?php
namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\Contribution;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate; 
use Muserpol\Models\City;
use Muserpol\Models\AffiliateState;
use Muserpol\Models\Category;
use Muserpol\Models\Degree;
use Muserpol\Models\PensionEntity;
use Muserpol\Models\User;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use Auth;
use Validator;
use DateTime;
use App;
use Muserpol\Helpers\Util;
use Muserpol\Models\Contribution\ContributionCommitment;
use Yajra\Datatables\DataTables;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\Voucher;
use Muserpol\Models\RetirementFund\RetirementFund;
use Log;
use Session;
use DB;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Muserpol\Models\Contribution\ContributionType;
use Muserpol\Policies\ReimbursementPolicy;
use Muserpol\Models\Contribution\ContributionRate;
class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInterest(Request $request)
    {
        //Obtiene el interes a partir del subsiguiente mes que debe pagar. Ej. de enero corre el interes desde marzo
        $contribution_rate = ContributionRate::where('month_year',date('Y').'-'.date('m').'-01')
                                                ->first();        
        $c_start_date =  Carbon::createFromDate($request->con['year'], $request->con['month'], '01')->addMonths(2);
        $c_end_date = Carbon::parse(Carbon::now()->toDateString());
        $dateStart = Carbon::createFromDate($request->con['year'], $request->con['month'], '01')->addMonths(2)->format('d/m/Y');
        $dateEnd = Carbon::parse(Carbon::now()->toDateString())->format('d/m/Y');
       $mount = ($contribution_rate['retirement_fund']+$contribution_rate['mortuary_quota'])/100*$request->con['sueldo'];
        $uri = 'https://www.bcb.gob.bo/calculadora-ufv/frmCargaValores.php?txtFecha=' . $dateStart . '&txtFechaFin=' . $dateEnd . '&txtMonto=' . $mount . '&txtCalcula=2';
        $foo = file_get_contents($uri);
        //return $foo;
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $json = '';
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($c_start_date->gt($c_end_date))
            $foo = "0,00";        
        //if( ($json = curl_exec($ch) ) === false)
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
    public function getMonthContributions($id)
    {
        $contributions=[];
        $lastMonths = Contribution::where('affiliate_id', $id)
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
                $contribution1 = array('year' => $month1->format('Y'), 'month' => $month1->format('m'), 'monthyear' => $month1->format('m-Y'), 'sueldo' => 0, 'fr' => 0, 'cm' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
                $contribution2 = array('year' => $month2->format('Y'), 'month' => $month2->format('m'), 'monthyear' => $month2->format('m-Y'), 'sueldo' => 0, 'fr' => 0, 'cm' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
                $contribution3 = array('year' => $month3->format('Y'), 'month' => $month3->format('m'), 'monthyear' => $month3->format('m-Y'), 'sueldo' => 0, 'fr' => 0, 'cm' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
                $contributions = array($contribution3, $contribution2, $contribution1);
            } 
            else 
            {
                //$contributions=[];
                for ($i = 0; $i < $diff; $i++) {                                    
                    $month_diff = Carbon::now()->subMonths($i + 1);
                    $month = explode('-', $month_diff);
                    $montyear = $month_diff->format('m-Y');
                    $contribution = array(
                        'year' => $month[0], 
                        'month' => $month[1], 
                        'monthyear' => $montyear, 
                        'sueldo' => 0, 
                        'fr' => 0, 
                        'cm' => 0, 
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
    public function storeDirectContribution(Request $request)
    {      

        //*********START VALIDATOR************//        
        $rules=[];        
//        if(!empty($request->aportes))
//        { 
            $has_commitment = false;
            $commitment = ContributionCommitment::where('affiliate_id',$request->afid)->where('state','ALTA')->first();
            if(!isset($commitment->id))
                $commitment = true;
            $valid_commitment = false;
                                   
            $commision_date = strtotime($commitment->commision_date);
            $commtiment_date = strtotime($commitment->commitment_date);
            $datediff = $commtiment_date - $commision_date;
            $datediff = round($datediff / (60 * 60 * 24));


            
            $biz_rules = [
                'has_commitment'    =>  $has_commitment?'required':'',
                'valid_commitment'  =>  $datediff>90?'required':''
            ];            
                        
            foreach ($request->aportes as $key => $ap)
            {                                            
                $aporte=(object)$ap;
                $cont = Contribution::where('affiliate_id',$request->afid)->where('month_year',$aporte->year.'-'.$aporte->month.'-01')->first();
                $has_contribution = false;
                if(isset($cont->id))
                    $has_contribution = true;
                
                $biz_rules = [
                    'has_contribution.'.$key    =>  $has_contribution?'required':'',
                ];
                
                $rules=array_merge($rules,$biz_rules);
                //$aporte=(object)$ap;
                $array_rules = [
                    'aportes.'.$key.'.sueldo' =>  'required|numeric|min:0',
                    'aportes.'.$key.'.fr' =>  'required|numeric',
                    'aportes.'.$key.'.cm' =>  'required|numeric',
                    'aportes.'.$key.'.subtotal' =>  'required|numeric',
                    'aportes.'.$key.'.interes' =>  'required|numeric',
                    'aportes.'.$key.'.year' =>  'required|numeric|min:1700',
                    'aportes.'.$key.'.month' =>  'required|numeric|min:1|max:12',
                ];
                $rules=array_merge($rules,$array_rules);
            }
        
        $rules = array_merge($rules,$biz_rules);
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){            
            return response()->json($validator->errors(), 406);
        }                
         //*********END VALIDATOR************//                               
        // Se guarda voucher fecha, total 1 reg
        $voucher_code = Voucher::select('id', 'code')->orderby('id', 'desc')->first();
        if (!isset($voucher_code->id))
            $code = Util::getNextCode(""); 
        else
            $code = Util::getNextCode($voucher_code->code);
        $voucher = new Voucher();
        $voucher->user_id = Auth::user()->id;
        $voucher->affiliate_id = $request->afid;
        $voucher->voucher_type_id = 1;//$request->tipo; 1 default as Pago de aporte directo
        $voucher->total = $request->total;
        $voucher->payment_date = Carbon::now();
        $voucher->code = $code;
        $voucher->save();      
        
        $affiliate = Affiliate::find($request->afid);
        $affiliate->affiliate_state_id = $request->tipo;
        $affiliate->save();
       // return $voucher;
        //return $request->aportes;
        $result = [];
        foreach ($request->aportes as $ap)  // guardar 1 a 3 reg en contribuciones
        {
            $aporte=(object)$ap;
            //sreturn $aporte->affiliate_id;
            $affiliate = Affiliate::find($request->afid);
            $contribution = new Contribution();
            $contribution->user_id = Auth::user()->id;
            $contribution->affiliate_id = $affiliate->id;
            $contribution->degree_id = $affiliate->degree_id;
            $contribution->unit_id = $affiliate->unit_id;
            $contribution->breakdown_id = $affiliate->breakdown_id;
            $contribution->category_id = $affiliate->category_id;
            $contribution->month_year = Carbon::createFromDate($aporte->year, $aporte->month,1);
            $contribution->type='Directo';     
            $contribution->base_wage = $aporte->sueldo;            
            $contribution->seniority_bonus = 0;
            $contribution->study_bonus = 0;
            $contribution->position_bonus = 0;
            $contribution->border_bonus = 0;
            $contribution->east_bonus = 0;
            $contribution->public_security_bonus = 0;
            $contribution->deceased = 0;
            $contribution->natality = 0;
            $contribution->lactation = 0;
            $contribution->prenatal = 0;
            $contribution->subsidy = 0;
            $contribution->gain = $aporte->sueldo;
            $contribution->payable_liquid = 0;
            $contribution->quotable = $aporte->sueldo;
            $contribution->retirement_fund = $aporte->fr;
            $contribution->mortuary_quota = $aporte->cm;
            $contribution->total = $aporte->subtotal;
            $contribution->interest = $aporte->interes;            
            $contribution->save();
            array_push($result, [
                'total'=>$contribution->total,
                'month_year'=>$aporte->year.'-'.$aporte->month.'-01',
                    ]);
            //Log::info(json_encode($contribution));
            //return $contribution;
        }
        
        $data = [
            'contribution'  =>  $result,
            'voucher_id'    => $voucher->id,
            'affiliate_id'  =>  $affiliate->id,
        ];
        return $data;
    }
    /**
     * Display the specified resource.
     *use Muserpol\Models\AffiliateState;
     * @param  \Muserpol\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function show(Affiliate $affiliate)
    {
        $this->authorize('view',new Contribution);
        $cities = City::all();
        $birth_cities = City::all()->pluck('name', 'id');
        $affiliate_states = AffiliateState::all()->pluck('name', 'id');
        $categories = Category::all()->pluck('name', 'id');
        $degrees = Degree::all()->pluck('name', 'id');
        $pension_entities = PensionEntity::all()->pluck('name', 'id');
        $data = [
            'affiliate' => $affiliate,
            'cities' => $cities,
            'birth_cities' => $birth_cities,
            'affiliate_states' => $affiliate_states,
            'categories' => $categories,
            'degrees' => $degrees,
            'pension_entities' => $pension_entities,
        ];
        return view('contribution.show', $data);
    }
    public function getAffiliateContributionsDatatables(DataTables $datatables, $affiliate_id)
    {
        $affiliate = Affiliate::find($affiliate_id);
        $reimbursements = $affiliate->reimbursements()->selectRaw("
            affiliate_id,
            month_year,
            concat('RE - ',  extract(year from month_year ))  as month_year_concat,
            null,
            null,
            null,
            base_wage,
            seniority_bonus,
            study_bonus,
            position_bonus,
            border_bonus,
            east_bonus,
            public_security_bonus,
            gain,
            quotable,
            retirement_fund,
            mortuary_quota,
            total,
            null,
            ' RE ' as type");
        $contributions = $affiliate->contributions()->selectRaw("
            affiliate_id,
            month_year,
            concat(extract(month from month_year), ' - ', extract(year from month_year)) as month_year_concat,
            degree_id,
            unit_id,
            item,
            base_wage,
            seniority_bonus,
            study_bonus,
            position_bonus,
            border_bonus,
            east_bonus,
            public_security_bonus,
            gain,
            quotable,
            retirement_fund,
            mortuary_quota,
            total,
            breakdown_id,
            type"
            )
            ->union($reimbursements)
            ->orderBy('month_year','desc')
            ->get()
            ;
        $query = $contributions;
        return $datatables->of($query)
            // ->editColumn('month_year', function ($contribution) {
                
            //     return Carbon::parse($contribution->month_year)->month . "-" . Carbon::parse($contribution->month_year)->year;
            // })
            ->editColumn('degree_id', function ($contribution) {
                return $contribution->degree_id ? $contribution->degree->hierarchy->code . "-" . $contribution->degree->code : null;
            })
            ->editColumn('unit_id', function ($contribution) {
                return $contribution->unit_id ? $contribution->unit->code : null;
            })
            ->editColumn('base_wage', function ($contribution) {
                return Util::formatMoney($contribution->base_wage);
            })
            ->editColumn('seniority_bonus', function ($contribution) {
                return Util::formatMoney($contribution->seniority_bonus);
            })
            ->editColumn('study_bonus', function ($contribution) {
                return Util::formatMoney($contribution->study_bonus);
            })
            ->editColumn('position_bonus', function ($contribution) {
                return Util::formatMoney($contribution->position_bonus);
            })
            ->editColumn('border_bonus', function ($contribution) {
                return Util::formatMoney($contribution->border_bonus);
            })
            ->editColumn('east_bonus', function ($contribution) {
                return Util::formatMoney($contribution->east_bonus);
            })
            ->editColumn('public_security_bonus', function ($contribution) {
                return Util::formatMoney($contribution->public_security_bonus);
            })
            ->editColumn('gain', function ($contribution) {
                return Util::formatMoney($contribution->gain);
            })
            ->editColumn('quotable', function ($contribution) {
                return Util::formatMoney($contribution->quotable);
            })
            ->editColumn('retirement_fund', function ($contribution) {
                return Util::formatMoney($contribution->retirement_fund);
            })
            ->editColumn('mortuary_quota', function ($contribution) {
                return Util::formatMoney($contribution->mortuary_quota);
            })
            ->editColumn('total', function ($contribution) {
                return Util::formatMoney($contribution->total);
            })
            ->editColumn('breakdown_id', function ($contribution) {
                return '<span data-toggle="tooltip" data-placement="top" title="' . ($contribution->breakdown->name ?? '') . '">' . $contribution->breakdown_id . '</span>';
            })
            ->rawColumns(['breakdown_id'])
            ->make(true);
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
    public function getAffiliateContributions(Affiliate $affiliate = null)
    {        
        
        //codigo para obtener totales para el resument
        $this->authorize('update',new Contribution);
        $contributions = Contribution::where('affiliate_id', $affiliate->id)->orderBy('month_year', 'DESC')->get();
        $reims = Reimbursement::where('affiliate_id', $affiliate->id)->get();
        $group = [];
        $group_reim = [];
        foreach ($reims as $reim)
            $group_reim[$reim->month_year] = $reim;
        $fondoret = 0;
        $quotaaid = 0;
        foreach ($contributions as $contribution) {
            $group[$contribution->month_year] = $contribution;
            $fondoret = $contribution->retirement_fund + $fondoret;
            $quotaaid = $contribution->mortuary_quota + $quotaaid;
        }
        $total = $fondoret + $quotaaid;
        $dateentry = Util::getStringDate($affiliate->date_entry);
        $categories = Category::get();
        $end = explode('-', $affiliate->date_entry);
        $newcontributions = [];
        $month_end = $end[1];
        $year_end = $end[0];
        $month_start = (date('m') - 1);
        $year_start = date('Y');
        $last_contribution = Contribution::where('affiliate_id',$affiliate->id)->orderBy('month_year','desc')->first();        
        $summary = array(
            'fondoret' => $fondoret,
            'quotaaid' => $quotaaid,
            'total' => $total,
            'dateentry' => $dateentry
        );
        $cities = City::all()->pluck('first_shortened', 'id');
        $cities_objects = City::all();
        $birth_cities = City::all()->pluck('name', 'id');
        //get Commitment data
        $commitment = ContributionCommitment::where('affiliate_id',$affiliate->id)->where('state','ALTA')->first();        
        if(!isset($commitment->id))
        {
            $commitment = new ContributionCommitment();
            $commitment->id = 0;
            $commitment->affiliate_id = $affiliate->id;
        }
        //RATES
        $rate = ContributionRate::where('month_year',date('Y').'-'.date('m').'-01')->first();
        $data = [
            'contributions' => $group,
            'reims' => $group_reim,
            'affiliate_id' => $affiliate->id,
            'categories' => $categories,
            'year_start' => $year_start,
            'year_end' => $year_end,
            'summary' => $summary,
            'affiliate' => $affiliate,
            'cities' => $cities,
            'cities_objects' => $cities_objects,
            'birth_cities' => $birth_cities,
            'new_contributions' => self::getMonthContributions($affiliate->id),
            'last_quotable' =>  $last_contribution->quotable ?? 0,
            'commitment'    =>  $commitment,
            'today_date'         =>  date('Y-m-d'),
            'rate'  =>  $rate,
        ];
        //return  date('Y-m-d');
         return view('contribution.affiliate_contributions_edit', $data);
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
              if(isset($request->base_wage[$key]))
                $input_data['base_wage'][$key]= strip_tags($request->base_wage[$key]);
              if(isset($request->gain[$key]))
                $input_data['gain'][$key]= strip_tags($request->gain[$key]);
              $input_data['total'][$key]= strip_tags($request->total[$key]);
        $array_rules = [
            'base_wage.'.$key =>  'numeric|min:0',
            'gain.'.$key =>  'numeric|min:1',
            'total.'.$key =>  'required|numeric|min:1'
            ];
            $rules=array_merge($rules,$array_rules);
        $array_messages = [
//            'base_wage.'.$key.'.numeric' => 'El valor de Sueldo debe ser numerico.',
//            'base_wage.'.$key.'.min'  =>  'El salario minimo es 2000.',
//            'gain.'.$key.'.numeric' => 'El campo debe ser numero.',
//            'gain.'.$key.'.min'  =>  'La cantidad ganada debe ser mayor a 0.', 
//            'total.'.$key.'.numeric' => 'El valor del Aporte debe ser numerico.',
//            'total.'.$key.'.min'  =>  'El aporte debe ser mayor a 0.'
        ];
        $messages=array_merge($messages, $array_messages);
        }   
            $validator = Validator::make($input_data,$rules,$messages);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
         //*********END VALIDATOR************//
        //return ;
        $this->authorize('update',new Contribution);
        $contributions = [];
        foreach ($request->iterator as $key => $iterator) {
            $contribution = Contribution::where('affiliate_id', $request->affiliate_id)->where('month_year', $key)->first();
            if (isset($contribution->id)) {
                $contribution->total = strip_tags($request->total[$key]) ?? $contribution->total;
                
                if(!isset($request->base_wage[$key]) || $contribution->base_wage == "")
                   $contribution->base_wage = 0;
                else
                    $contribution->base_wage = strip_tags($request->base_wage[$key]) ?? $contribution->base_wage;
                
                if ($request->category[$key] != $contribution->category_id) {
                    $category = Category::find($request->category[$key]);
                    $contribution->category_id = $category->id;
                    //return $category->percentage." ".$contribution->base_wage;
                    $contribution->seniority_bonus = $category->percentage * $contribution->base_wage;
                }
                
                if(!isset($request->gain[$key]) || $contribution->gain == "")
                    $contribution->gain = 0;
                else
                    $contribution->gain = strip_tags($request->gain[$key]) ?? $contribution->gain;
                $contribution->save();
                array_push($contributions, $contribution);
            } else {
//                $contribution = new Contribution();
//                $contribution->user_id = Auth::user()->id;
//                $contribution->total = $total;
                $affiliate = Affiliate::find($request->affiliate_id);
                $contribution = new Contribution();
                $contribution->user_id = Auth::user()->id;
                $contribution->affiliate_id = $request->affiliate_id;
                $contribution->degree_id = $affiliate->degree_id;
                $contribution->unit_id = $affiliate->unit_id;
                $contribution->breakdown_id = $affiliate->breakdown_id;
                
                if(!isset($request->base_wage[$key]) || $contribution->base_wage == "")
                    $contribution->base_wage = 0;
                else
                    $contribution->base_wage = strip_tags($request->base_wage[$key]) ?? 0;
                $category = Category::find($request->category[$key]);
                $contribution->category_id = $category->id;
                //$data = $contribution->base_wage * 123;
                $contribution->seniority_bonus = $category->percentage * $contribution->base_wage;
                $contribution->study_bonus = 0;
                $contribution->position_bonus = 0;
                $contribution->border_bonus = 0;
                $contribution->east_bonus = 0;
                $contribution->quotable = 0;
                $contribution->month_year = $key;
                
                if(!isset($request->gain[$key]) || $contribution->gain == "")
                    $contribution->gain = 0;
                else
                    $contribution->gain = strip_tags($request->gain[$key]) ?? 0;
                $contribution->retirement_fund = 0;
                $contribution->mortuary_quota = 0;
                $contribution->total = strip_tags($request->total[$key]) ?? 0;
                //$contribution->interes = 0;
                $contribution->type = 'Planilla';
                $contribution->save();
                array_push($contributions, $contribution);
            }
        }
        return $contributions;
        //return json_encode($contribution);
    }
}
    public function generateContribution(Affiliate $affiliate) 
    {
        $this->authorize('create',Contribution::class);
        $contributions = self::getMonthContributions($affiliate->id);
        return View('contribution.create', compact('affiliate', 'contributions'));
    }

    public function selectContributions($ret_fun_id)
    {
        // $contributions = Contribution::where('affiliate_id',$affiliate_id)->take(10)->get();
        $ret_fun = RetirementFund::find($ret_fun_id);
        // $contribution =DB::table('contributions')->where('affiliate_id',$ret_fun->affiliate_id)->whereNull('deleted_at')->get();
        // return $contribution;
        $con_type = false;
        $contributions= DB::table('contributions')->join('categories','contributions.category_id','categories.id')
                                                  ->join('contribution_types','contribution_types.id','contributions.contribution_type_id')
                                                  ->where('contributions.affiliate_id',$ret_fun->affiliate_id)
                                                //   ->whereNull('contributions.deleted_at')
                                                  ->select('contributions.id','contributions.base_wage','contributions.total','contributions.gain','contributions.retirement_fund','contributions.contribution_type_id as breakdown_id','contribution_types.name as breakdown_name','contributions.category_id','categories.name as category_name','contributions.month_year')
                                                //   ->take(10)
                                                  ->orderBy('contributions.month_year', 'desc')
                                                  ->get();
        // $contributions = [];
    //    return $contributions->count();
       
        if(sizeof($contributions) == 0){
          $contributions= DB::table('contributions')->join('categories','contributions.category_id','categories.id')
                                                    ->join('breakdowns','contributions.breakdown_id','breakdowns.id')
                                                    ->where('contributions.affiliate_id',$ret_fun->affiliate_id)
                                                    // ->whereNull('contributions.deleted_at')
                                                    ->select('contributions.id','contributions.base_wage','contributions.total','contributions.gain','contributions.retirement_fund','contributions.breakdown_id','breakdowns.name as breakdown_name','contributions.category_id','categories.name as category_name','contributions.month_year')
                                                //   ->take(10)
                                                    ->orderBy('contributions.month_year', 'desc')
                                                    ->get();
           $con_type=true;
        }  
        
        // return $contributions;
       
        $contribution_types = DB::table('contribution_types')->select('id','name')->get();
        $data =   array('contributions' => $contributions,
                        'con_type'=>$con_type ,
                        'contribution_types'=> $contribution_types,
                        'url_certification'=> url('ret_fun/'.$ret_fun->id.'/print/certification'),
                        'url_certification_availability'=> url('ret_fun/'.$ret_fun->id.'/print/cer_availability'),
                        'url_certification_itemcero'=> url('ret_fun/'.$ret_fun->id.'/print/cer_itemcero'),
                        'ret_fun'=>$ret_fun);
        return view('contribution.select',$data);
    }
    public function saveContributions(Request $request)
    {   
        // return $request->all();
        $ret_fun = RetirementFund::find($request->ret_fun_id);
        // return $ret_fun;

        // $ret_fun->contributions()->attach($sixty_id);
        Log::info('imprimiendo contribuciones');
        $i=0;
        foreach ($request->list_aportes as $obj) {
            # code...
             $aporte = (object) $obj;
             if($aporte->id == 0)
             {
                    Log::info('intentando guardar objeto');
                    Log::info(json_encode($aporte));
                    $contribution = new Contribution;
                    $contribution->user_id = Auth::user()->id;
                    $contribution->affiliate_id = $ret_fun->affiliate_id;
                    $contribution->type = 'Planilla';
                    $contribution->base_wage =0;
                    $contribution->month_year = $aporte->month_year;
                    $contribution->seniority_bonus = 0;
                    $contribution->study_bonus = 0;
                    $contribution->position_bonus = 0;
                    $contribution->border_bonus = 0;
                    $contribution->east_bonus = 0;
                    $contribution->dignity_pension = 0;
                    $contribution->gain = 0;
                    $contribution->quotable = 0;
                    $contribution->retirement_fund = 0;
                    $contribution->mortuary_quota = 0;
                    $contribution->total = 0;
                    $contribution->contribution_type_id = $aporte->breakdown_id;
                    $contribution->category_id =1;
                    $contribution->save();

             }else{
                 # code...
                    $contribution = Contribution::find($aporte->id);
                    $contribution->contribution_type_id = $aporte->breakdown_id;
                    $contribution->save();
             }
            $i++;
            Log::info('i: '.$i.' id:'.$contribution->id);
        }
        return $request->all();
        // return redirect('/');     
    }
  
    public function printCertification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $servicio = ContributionType::where('name','=','Servicio')->first();
        $item_cero = ContributionType::where('name','=','Item 0')->first();
        $quantity = Util::getRetFunCurrentProcedure()->contributions_number;
        $contributions_sixty = Contribution::where('affiliate_id', $affiliate->id)
                        ->where(function ($query) use ($servicio,$item_cero){
                            $query->where('contribution_type_id',$servicio->id)
                            ->orWhere('contribution_type_id',$item_cero->id);
                        })
                        ->orderBy('month_year','desc')
                        ->take($quantity)
                        ->get();                                          
        $contributions = $contributions_sixty->sortBy('month_year')->all();                           
        $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
                        ->orderBy('month_year')
                        ->get();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "CERTIFICACION DE APORTES";
        $subtitle ="Cuenta Individual";
        $number = $retirement_fund->code;
        $date = Util::getStringDate($retirement_fund->reception_date);        
        $degree = Degree::find($affiliate->degree_id);
        $exp = City::find($affiliate->city_identity_card_id);
        $exp = ($exp==Null)? "-": $exp->first_shortened;
        $dateac = Carbon::now()->format('d/m/Y');
        $place = City::find($retirement_fund->city_start_id); 
        $num=0;       
        $username = Auth::user()->username;
        $pdftitle = "Cuentas Individuales";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);       
        return \PDF::loadView('contribution.print.certification_contribution', compact('num','subtitle','place','retirement_fund','reimbursements','dateac','exp','degree','contributions','affiliate','title', 'username','institution', 'direction', 'unit', 'date', 'header', 'number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printCertificationAvailability($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $disponibilidad = ContributionType::where('name','=','Disponibilidad')->first();
        $contributions = Contribution::where('affiliate_id', $affiliate->id)
                        ->orderBy('month_year')
                        ->get();
        $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
                        ->orderBy('month_year')
                        ->get();                          
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "CERTIFICACION DE APORTES EN DISPONIBILIDAD";
        $subtitle ="Cuenta Individual";
        $number = $retirement_fund->code;
        $date = Util::getStringDate($retirement_fund->reception_date);
        $degree = Degree::find($affiliate->degree_id);
        $exp = City::find($affiliate->city_identity_card_id);
        $exp = ($exp==Null)? "-": $exp->first_shortened;
        $dateac = Carbon::now()->format('d/m/Y');
        $place = City::find($retirement_fund->city_start_id); 
        $num=0;             
        $username = Auth::user()->username;
        $pdftitle = "Cuentas Individuales";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);
        //total de los aportes
        $aporte=0;
        foreach($contributions as $contribution){
            if($contribution->contribution_type_id==$disponibilidad->id){
                $aporte=$aporte+$contribution->total;
            }
        }
        return \PDF::loadView('contribution.print.certification_availability', compact('num','disponibilidad','aporte','subtitle','place','retirement_fund','reimbursements','dateac','exp','degree','contributions','affiliate','title', 'username','institution', 'direction', 'unit', 'date','header', 'number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printCertificationItem0($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $itemcero = ContributionType::where('name','=','Item 0')->first();
        $contributions = Contribution::where('affiliate_id', $affiliate->id)
                        ->orderBy('month_year')
                        ->get();
        $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
                        ->orderBy('month_year')
                        ->get();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "CERTIFICACION DE CUENTAS INDIVIDUALES ITEM 0";
        $subtitle = "Cuenta Individual";
        $number = $retirement_fund->code;
        $date = Util::getStringDate($retirement_fund->reception_date);
        $degree = Degree::find($affiliate->degree_id);
        $exp = City::find($affiliate->city_identity_card_id);
        $exp = ($exp==Null)? "-": $exp->first_shortened;
        $dateac = Carbon::now()->format('d/m/Y');
        $place = City::find($retirement_fund->city_start_id);              
        $username = Auth::user()->username;
        $pdftitle = "Cuentas Individuales";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);
        return \PDF::loadView('contribution.print.certification_item0', compact('itemcero','subtitle','place','retirement_fund','reimbursements','dateac','exp','degree','contributions','affiliate','title', 'username','institution', 'direction', 'unit', 'date','header', 'number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    } 
}