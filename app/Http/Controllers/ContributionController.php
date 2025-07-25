<?php
namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\Contribution;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\City;
use Muserpol\Models\Address;
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
use Muserpol\Helpers\ID;
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
use Muserpol\Models\Contribution\ContributionProcess;
use DateInterval;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\Contribution\ContributionTypeQuotaAid;
use Muserpol\Models\QuotaAidMortuary\QuotaAidProcedure;
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
        $c_end_date = Carbon::parse(Util::parseBarDate($request->dateEnd));        
        $dateStart = Carbon::createFromDate($request->con['year'], $request->con['month'], '01')->addMonths(2)->format('d/m/Y');
        $dateEnd = $request->dateEnd;
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
            return response('error', 500);
        }
        else
        {
            return $foo;
        }
    }
    public function getMonthContributions($id, $date){    
        // $today = date('Y-m-d');//'2018-05-01';//date('Y-m-d');
        $today = $date;//'2018-05-01';//date('Y-m-d');
        Carbon::useMonthsOverflow(false);        
        $start_date = Carbon::parse($today);
        $start_date->subMonth(); //contributions are paid when month finishes                        
        $end_date = '';
        $end_date = Carbon::parse($today);
        $regulairzation = Util::getRetFunCurrentProcedure()->contribution_regulate_days;            
        $end_date->subDay($regulairzation);
        $end_date->subMonth();//one month of arrear        
        $contributions_array  = Contribution::whereDate('month_year','<=',$start_date->format('Y-m')."-01")->whereDate('month_year','>=',$end_date->format('Y-m')."-01")->where('affiliate_id',$id)->pluck('month_year')->toArray();                        
        $iterator_date = $start_date;
        $contributions = [];
        while($iterator_date->format('Y-m') >= $end_date->format('Y-m') ){
            if(!in_array($iterator_date->format('Y-m')."-01",$contributions_array)){                
                $contribution = array(
                    'year' => $iterator_date->format('Y'), 
                    'month' => $iterator_date->format('m'), 
                    'monthyear' => $iterator_date->format('m-Y'), 
                    'sueldo' => 0, 
                    'fr' => 0, 
                    'cm' => 0, 
                    'interes' => 0, 
                    'subtotal' => 0, 
                    'affiliate_id' => $id,
                    'type'  =>  'N'
                    );
                array_push($contributions,$contribution);
            }                                    
            $iterator_date->subMonth();                
        }
        $contributions = array_reverse($contributions);
        return $contributions;
    }
    public function getContributionRate($date)
    {
        $date = Carbon::parse($date)->format('Y-m');
        $rate = ContributionRate::where('month_year',$date.'-01')->first();
        if ($rate) {
            return $rate;
        }
        return null;
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
            $datediff = 0;
            $commitment = ContributionCommitment::where('affiliate_id',$request->afid)->where('state','ALTA')->first();
            if(!isset($commitment->id))
                $has_commitment = true;
            else
            {
                $commision_date = strtotime($commitment->commision_date) ;
                $commtiment_date = strtotime($commitment->commitment_date);
                $datediff = $commtiment_date - $commision_date;
                $datediff = round($datediff / (60 * 60 * 24));
            }
           
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
        // $voucher_code = Voucher::select('id', 'code')->orderby('id', 'desc')->first();
        // if (!isset($voucher_code->id))
        //     $code = Util::getNextCode(""); 
        // else
        //     $code = Util::getNextCode($voucher_code->code);

        // $voucher = new Voucher();
        // $voucher->user_id = Auth::user()->id;
        // $voucher->affiliate_id = $request->afid;
        // $voucher->voucher_type_id = 1;//$request->tipo; 1 default as Pago de aporte directo
        // $voucher->total = $request->total;
        // $voucher->payment_date = Carbon::now();
        // $voucher->code = $code;
        // $voucher->paid_amount = $request->paid;
        // $voucher->bank = $request->bank;
        // $voucher->bank_pay_number = $request->bank_pay_number;
        // $voucher->save();      
                
        

        $process = new ContributionProcess();
        $process->affiliate_id = $affiliate->id;
        $process->user_id = Auth::user()->id;
        $process->city_id = Auth::user()->city_id;
        $process->wf_state_current_id = 50;
        $process->workflow_id = 7;
        $process->procedure_modality_id = 1;
        $process->date = date('Y-m-d');
        $process->code = "1/2018";
        $process->inbox_state = true;
        $process->save();
        
        $result = [];
        $stored_contributions = [];
        $contribution_ids = [];  
        foreach ($request->aportes as $ap)  // guardar 1 a 3 reg en contribuciones
        {
            $aporte=(object)$ap;            
            $affiliate = Affiliate::find($request->afid);
            if($aporte->type == 'R'){
                $category = Category::find($request->category);    
                               
                $contribution = new Reimbursement();
                $contribution->user_id = Auth::user()->id;
                $contribution->affiliate_id = $affiliate->id;
                $contribution->month_year = $aporte->year.'-'.$aporte->month.'-01';
                $contribution->type = "Directo";                
                $contribution->base_wage = $aporte->sueldo;                
                        
                $contribution->seniority_bonus = 0;
                $contribution->study_bonus = 0;
                $contribution->position_bonus = 0;
                $contribution->border_bonus = 0;
                $contribution->east_bonus = 0;
                $contribution->public_security_bonus = 0;
                $contribution->gain = $aporte->sueldo;
                $contribution->payable_liquid = 0;
                $contribution->quotable = $aporte->sueldo;
                $contribution->retirement_fund = $aporte->fr;
                $contribution->mortuary_quota = $aporte->cm;
                $contribution->total = $aporte->subtotal;
                $contribution->interest = $aporte->interes;
                $contribution->subtotal = 0;
                $contribution->valid = false;
                $contribution->save();
                $contribution->type = "R";
            }
            else{
                $contribution = new Contribution();
                $contribution->user_id = Auth::user()->id;
                $contribution->affiliate_id = $affiliate->id;
                $contribution->degree_id = $affiliate->degree_id;
                $contribution->unit_id = $affiliate->unit_id;
                $contribution->breakdown_id = $affiliate->breakdown_id;
                $contribution->category_id = $affiliate->category_id;            
                $contribution->month_year = $aporte->year.'-'.$aporte->month.'-01';
                $contribution->type='Directo';     
                $contribution->base_wage = $aporte->sueldo;            
                $contribution->seniority_bonus = 0;
                $contribution->study_bonus = 0;
                $contribution->position_bonus = 0;
                $contribution->border_bonus = 0;
                $contribution->east_bonus = 0;
                $contribution->public_security_bonus = 0;
                $contribution->gain = $aporte->sueldo;
                $contribution->payable_liquid = 0;
                $contribution->quotable = $aporte->sueldo;
                $contribution->retirement_fund = $aporte->fr;
                $contribution->mortuary_quota = $aporte->cm;
                $contribution->total = $aporte->subtotal;
                $contribution->interest = $aporte->interes;        
                $contribution->valid = false;
                $contribution->breakdown_id = 3;
                $contribution->save();
            }
            array_push($contribution_ids,$contribution->id);
            array_push($result, [
                'total'=>$contribution->total,
                'month_year'=>$aporte->year.'-'.$aporte->month.'-01',
                    ]);
            array_push($stored_contributions,$contribution);            
        }
        // $process->contributions()->attach($contribution_ids);
        $data = [
            'contribution'  =>  $result,
            'contributions'  =>  $stored_contributions,
            //'voucher_id'    => $voucher->id,
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
            ->orderBy('month_year')
            ->orderBy('type')
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
                
    }
    public function directContributions(Affiliate $affiliate = null){
        $commitment = ContributionCommitment::where('affiliate_id',$affiliate->id)->where('state','ALTA')->first();
        $user = Auth::user();
         if (!isset($commitment->id) &&  !$user->can('create', ContributionCommitment::class)) {
            Session::flash('message','No se encontró compromiso de pago');
            return redirect('affiliate/'.$affiliate->id);    
        }
        // if(!isset($commitment->id))
        // {
        //     $commitment = new ContributionCommitment();
        //     $commitment->id = 0;
        //     $commitment->affiliate_id = $affiliate->id;
        // }

        $contributions = Contribution::where('affiliate_id', $affiliate->id)->orderBy('month_year', 'DESC')->get();
        $last_contribution = $contributions->first();

        // $rate = ContributionRate::where('month_year',date('Y').'-'.date('m').'-01')->first();

        // $summary = array(
        //    'fondoret' => $contributions->sum('retirement_fund'),
        //    'quotaaid' => $contributions->sum('mortuary_quota'),
        //    'total' => $contributions->sum('total'),
        //    'interest'  =>  $contributions->sum('interest'),
        //    'dateentry' => Util::getStringDate(Util::parseMonthYearDate($affiliate->date_entry))
        // );

        //  0 is sequence number of regional
        $is_regional = in_array(0, WorkflowState::where('role_id', Util::getRol()->id)->pluck('sequence_number')->toArray());
        $data = [
            // 'new_contributions' => $this->getMonthContributions($affiliate->id),            
            'commitment'    =>  $commitment,
            'affiliate' =>  $affiliate,
            // 'summary'   =>  $summary,
            'last_quotable' =>  $last_contribution->quotable ?? 0,
            'today_date'    =>  date('Y-m-d'),
            'is_regional'   =>  $is_regional,
            // 'rate'  =>  $rate,        
        ];
        return $data;
        return view('contribution.affiliate_direct_contributions', $data);        
    }
    public function getContributionsByMonth(Affiliate $affiliate = null){
        $contributions =  Contribution::where('affiliate_id',$affiliate->id)->pluck('total','month_year')->toArray();        
        $end = explode('-', Util::parseMonthYearDate($affiliate->date_entry));
        $month_end = $end[1];
        $year_end = $end[0];
        if($affiliate->date_last_contribution)
            $start = explode('-', Util::parseMonthYearDate($affiliate->date_last_contribution));  
        else
            $start = explode('-', date('Y-m-d'));  
        $month_start = $start[1];
        $year_start = $start[0];
        $data = [
            'contributions' =>  $contributions,
            'month_end' =>  $month_end,
            'month_start'  =>   $month_start,
            'year_end'  =>  $year_end,
            'year_start'    =>  $year_start,
        ];
        return $data;
        //return view('contribution.affiliate_contribution_show', $data);
//return $affiliate->id;
  //      return 334;
    }
    public function getAffiliateContributions(Affiliate $affiliate = null)
    {                
        //codigo para obtener totales para el resument        
        $this->authorize('update',new Contribution);
        $date_entry =$affiliate->date_entry;
        $date_last_contribution = $affiliate->date_last_contribution;     
        if(!$affiliate->date_last_contribution)
            $date_last_contribution  =   date('Y-m-d');
        if(!$date_entry || !$date_last_contribution){
            Session::flash('message','Verifique la fecha de entrada y ultimo aporte del afiliado antes de continuar');
            return redirect('affiliate/'.$affiliate->id);
        }
        
        $contributions = Contribution::with('category')->where('affiliate_id', $affiliate->id)->orderBy('month_year', 'DESC')->get();
        
        $reims = Reimbursement::where('affiliate_id', $affiliate->id)->get();
        $group = [];
        $group_reim = [];
        foreach ($reims as $reim){
            $group_reim[$reim->month_year] = $reim;        
        }
        foreach ($contributions as $contribution) {
            $group[$contribution->month_year] = $contribution;
        }    
        $summary = array(
            'fondoret' => $contributions->sum('retirement_fund'),
            'quotaaid' => $contributions->sum('mortuary_quota'),
            'total' => $contributions->sum('total'),
            'interest'  =>  $contributions->sum('interest'),
            'dateentry' => Util::getStringDate(Util::parseMonthYearDate($affiliate->date_entry))
        );        
            
        $categories = Category::get();
        $cities = City::all()->pluck('first_shortened', 'id');
        $cities_objects = City::all();
        $birth_cities = City::all()->pluck('name', 'id');                
        

        $end = explode('-', Util::parseMonthYearDate($affiliate->date_entry));
        $month_end = $end[1];
        $year_end = $end[0];
        
        if($affiliate->date_last_contribution_reinstatement) {
            $start = explode('-', Util::parseMonthYearDate($affiliate->date_last_contribution_reinstatement));      
        } elseif ($affiliate->date_last_contribution) {
            $start = explode('-', Util::parseMonthYearDate($affiliate->date_last_contribution));      
        } else {
            $start = explode('-', date('Y-m-d'));              
        }
        $month_start = $start[1];
        $year_start = $start[0];                
        // $commitment = ContributionCommitment::where('affiliate_id',$affiliate->id)->where('state','ALTA')->first();        
        // if(!isset($commitment->id))
        // {
        //     $commitment = new ContributionCommitment();
        //     $commitment->id = 0;
        //     $commitment->affiliate_id = $affiliate->id;
        // }
        //direccion del afiliado
        if (! sizeOf($affiliate->address) > 0) {
            $affiliate->address[] = new Address();
        }
        
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
        //    'commitment'    =>  $commitment,
            'today_date'         =>  date('Y-m-d'),            
        ];        
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
            {  if(isset($request->total[$key]) && $request->total[$key]>0) {         
                $contribution = Contribution::where('affiliate_id', $request->affiliate_id)->where('month_year', $key)->first();
                if(!isset($contribution->id)) {
                    if(isset($request->base_wage[$key])) {
                        $input_data['base_wage'][$key]= strip_tags($request->base_wage[$key]);
                    }
                    if(isset($request->gain[$key])) {
                        $input_data['gain'][$key]= strip_tags($request->gain[$key]);
                    }
                    if(isset($request->quotable[$key])) {
                        $input_data['quotable'][$key]= strip_tags($request->quotable[$key]);
                    }
                    if(isset($request->total[$key])) {
                        $input_data['total'][$key]= strip_tags($request->total[$key]);
                    }
                    if(isset($request->retirement_fund[$key])) {
                        $input_data['retirement_fund'][$key]= strip_tags($request->retirement_fund[$key]);
                    }
                    if(isset($request->mortuary_quota[$key])) {
                        $input_data['mortuary_quota'][$key]= strip_tags($request->mortuary_quota[$key]);
                    }                    

                    $array_rules = [
                        'base_wage.'.$key =>  'numeric|min:0',
                        'gain.'.$key =>  'numeric|min:0',
                        'quotable.'.$key =>  'numeric|min:0',
                        'total.'.$key =>  'required|numeric|min:1',
                        'retirement_fund.'.$key =>  'numeric|min:0',
                        'mortuary_quota.'.$key =>  'numeric|min:0',
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
            }
        }

        $validator = Validator::make($input_data,$rules,$messages);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
         //*********END VALIDATOR************//
   
        $this->authorize('update',new Contribution);
        $contributions = [];
  
        foreach ($request->iterator as $key => $iterator) {

            $contribution = Contribution::where('affiliate_id', $request->affiliate_id)->where('month_year', $key)->first();
     
                if (isset($contribution->id)) {//update
                    //Verifica los otrso parametros
                    if(isset($request->base_wage[$key]) && $request->base_wage[$key] != "")
                        $contribution->base_wage = strip_tags($request->base_wage[$key]) ?? $contribution->base_wage;
                    else
                        $contribution->base_wage = $contribution->base_wage;

                    if(isset($request->seniority_bonus[$key]) && $request->seniority_bonus[$key] != "")
                        $contribution->seniority_bonus = strip_tags($request->seniority_bonus[$key]) ?? $contribution->seniority_bonus;
                    else
                        $contribution->seniority_bonus = $contribution->seniority_bonus;

                    //obtener categoria
                    if ((isset($request->base_wage[$key]) || isset($request->seniority_bonus[$key]) && $contribution->unit_id != 21)) {
                        $categoryId = $this->assignCategoryToContribution($contribution);

                        if ($categoryId) {
                            $contribution->category_id = $categoryId;
                        } else {
                            return response()->json([
                                'error' => 'No se encontró una categoría correspondiente al bono de antigüedad.'
                            ], 404);
                        }
                    }else {
                        $contribution->category_id = null;
                    }

                    if (isset($request->study_bonus[$key])) {
                        $contribution->study_bonus = is_numeric($request->study_bonus[$key]) ? $request->study_bonus[$key] : 0;
                    }

                    if(isset($request->position_bonus[$key]))
                        $contribution->position_bonus = is_numeric($request->position_bonus[$key]) ? $request->position_bonus[$key] : 0;

                    if(isset($request->border_bonus[$key]))
                        $contribution->border_bonus = is_numeric($request->border_bonus[$key]) ? $request->border_bonus[$key] : 0;

                    if(isset($request->east_bonus[$key]))
                        $contribution->east_bonus = is_numeric($request->east_bonus[$key]) ? $request->east_bonus[$key]: 0;

                    if(isset($request->gain[$key]) && $request->gain[$key] != "")
                        $contribution->gain = strip_tags($request->gain[$key]) ?? $contribution->gain;
                    else
                        $contribution->gain = $contribution->gain;

                    if(isset($request->payable_liquid[$key]) && $request->payable_liquid[$key] != "")
                        $contribution->payable_liquid = strip_tags($request->payable_liquid[$key]) ?? $contribution->payable_liquid;
                    else
                        $contribution->payable_liquid = $contribution->payable_liquid;

                    if(isset($request->quotable[$key]) && $request->quotable[$key] != "")
                        $contribution->quotable = strip_tags($request->quotable[$key]) ?? $contribution->quotable;
                    else
                        $contribution->quotable = $contribution->quotable;

                    if(isset($request->total[$key]) && $request->total[$key] != "")
                        $contribution->total = strip_tags($request->total[$key]) ?? $contribution->total;
                    else
                        $contribution->total = $contribution->total;

                    $rate=ContributionRate::where('month_year', $key)->first();
                    //Distribución porcentual de aportes para casos anteriores a enero 1999, solo para estos casos se hace el calculo automatico
                    if($contribution->month_year <= '1999-01-01') {
                        if($contribution->month_year <='1987-04-01'){
                            $contribution->retirement_fund = round((floatval($contribution->total) * floatval($rate->retirement_fund))/(floatval($rate->retirement_fund)+floatval($rate->fcsspn)),2);
                            $contribution->mortuary_quota = 0;
                        }else{
                            $contribution->retirement_fund = round((floatval($contribution->total) * floatval($rate->retirement_fund))/(floatval($rate->retirement_fund)+floatval($rate->mortuary_quota)),2);
                            $contribution->mortuary_quota = $contribution->total - $contribution->retirement_fund;
                        }
                    }else{
                        //envia los parametros FR,QM

                        if(isset($request->retirement_fund[$key]) && $request->retirement_fund[$key] != ""){
                            $contribution->retirement_fund = strip_tags($request->retirement_fund[$key]);
                        }
                        if(isset($request->mortuary_quota[$key]) && $request->mortuary_quota[$key] != ""){
                            $contribution->mortuary_quota = strip_tags($request->mortuary_quota[$key]);
                        }
                    }
                    $contribution->user_id = Auth::user()->id;
                    $contribution->save();
                    array_push($contributions, $contribution);

                } else {//Create
                    if(isset($request->total[$key]) && $request->total[$key]>0) {
                        $affiliate = Affiliate::find($request->affiliate_id);
                        $contribution = new Contribution();
                        $contribution->user_id = Auth::user()->id;
                        $contribution->affiliate_id = $request->affiliate_id;
                        $contribution->degree_id = $affiliate->degree_id;
                        $contribution->unit_id = $affiliate->unit_id;
                        $contribution->breakdown_id = $affiliate->breakdown_id;
                        $contribution->month_year = $key;
                        
                        if(!isset($request->base_wage[$key]))
                            $contribution->base_wage = 0;
                        else
                            $contribution->base_wage = strip_tags($request->base_wage[$key]) ?? 0;

                        if(!isset($request->seniority_bonus[$key]))
                            $contribution->seniority_bonus = 0;
                        else
                            $contribution->seniority_bonus = strip_tags($request->seniority_bonus[$key]) ?? 0;
                      
                        //obtener categoria
                        if ((isset($request->base_wage[$key]) || isset($request->seniority_bonus[$key])) && $affiliate->unit_id != 21) {
                            $categoryId = $this->assignCategoryToContribution($contribution);

                            if ($categoryId) {
                                $contribution->category_id = $categoryId;
                            } else {
                                // Retorna un error si no se encontró la categoría
                                return response()->json([
                                    'error' => 'No se encontró una categoría correspondiente al bono de antigüedad.'
                                ], 404);
                            }
                        }else {
                            $contribution->category_id = null;
                        }

                        if(!isset($request->study_bonus[$key]))
                            $contribution->study_bonus = 0;
                        else
                            $contribution->study_bonus = strip_tags($request->study_bonus[$key]) ?? 0;

                        if(!isset($request->position_bonus[$key]))
                            $contribution->position_bonus = 0;
                        else
                            $contribution->position_bonus = strip_tags($request->position_bonus[$key]) ?? 0;

                        if(!isset($request->border_bonus[$key]))
                            $contribution->border_bonus = 0;
                        else
                            $contribution->border_bonus = strip_tags($request->border_bonus[$key]) ?? 0;

                        if(!isset($request->east_bonus[$key]))
                            $contribution->east_bonus = 0;
                        else
                            $contribution->east_bonus = strip_tags($request->east_bonus[$key]) ?? 0;

                        if(!isset($request->gain[$key]))
                            $contribution->gain = 0;
                        else
                            $contribution->gain = strip_tags($request->gain[$key]) ?? 0;

                        if(!isset($request->payable_liquid[$key]))
                            $contribution->payable_liquid = 0;
                        else
                            $contribution->payable_liquid = strip_tags($request->payable_liquid[$key]) ?? 0;

                        if(!isset($request->quotable[$key]))
                            $contribution->quotable = 0;
                        else
                            $contribution->quotable = strip_tags($request->quotable[$key]) ?? 0;                      

                        if(!isset($request->total[$key]))
                            $contribution->total = 0;
                        else
                            $contribution->total = strip_tags($request->total[$key]) ?? 0;

                        $rate=ContributionRate::where('month_year', $key)->first();

                         //Distribución porcentual de aportes para casos anteriores a enero 1999, solo para estos casos se hace el calculo automatico
                        if($contribution->month_year <= '1999-01-01') {

                            if($contribution->month_year <='1987-04-01'){
                                $contribution->retirement_fund = round((floatval($contribution->total) * floatval($rate->retirement_fund))/(floatval($rate->retirement_fund)+floatval($rate->fcsspn)),2);
                                $contribution->mortuary_quota = 0;
                            }else{
                                $contribution->retirement_fund = round((floatval($contribution->total) * floatval($rate->retirement_fund))/(floatval($rate->retirement_fund)+floatval($rate->mortuary_quota)),2);
                                $contribution->mortuary_quota = $contribution->total - $contribution->retirement_fund;
                            }
                        }else{
                            if(!isset($request->retirement_fund[$key]))
                                $contribution->retirement_fund = 0;
                            else
                                $contribution->retirement_fund = strip_tags($request->retirement_fund[$key]) ?? 0;
                            
                            if(!isset($request->mortuary_quota[$key]))
                                $contribution->mortuary_quota = 0;
                            else
                                $contribution->mortuary_quota = strip_tags($request->mortuary_quota[$key]) ?? 0;  
                        }
                        $contribution->type = 'Planilla';
                        $contribution->save();
                        array_push($contributions, $contribution);
                    }
                }
        }
        return $contributions;
        //return json_encode($contribution);
    }
}
    public function assignCategoryToContribution($contribution)
    {
        $percentage_category = round(floatval($contribution->seniority_bonus / $contribution->base_wage), 2);
    
        $closestCategory = Category::select('id', 'percentage')
                                   ->where('percentage', '=', $percentage_category)
                                   ->first();
        if ($closestCategory) {
            return $closestCategory->id;
        }    
        return false;
    }

    public function generateContribution(Affiliate $affiliate) 
    {
        $this->authorize('create',Contribution::class);
        $contributions = self::getMonthContributions($affiliate->id);
        return View('contribution.create', compact('affiliate', 'contributions'));
    }

    public function selectContributions($ret_fun_id)
    {
        $ret_fun = RetirementFund::find($ret_fun_id);
        $affiliate = $ret_fun->affiliate;

        $date_entry = Util::parseMonthYearDate($affiliate->date_entry);
        $date_last_contribution = Util::parseMonthYearDate($affiliate->date_last_contribution);

        $date_entry_reinstatement = Util::parseMonthYearDate($affiliate->date_entry_reinstatement);
        $date_last_contribution_reinstatement = Util::parseMonthYearDate($affiliate->date_last_contribution_reinstatement);
        $ret_fun_index = $ret_fun->procedureIndex();
        if($ret_fun_index === false) {
            return "Error";
        }

        $start_date = '';
        $end_date = '';
        if ($ret_fun_index == 0) {
            if (!(Carbon::hasFormat($date_entry, 'Y-m-d') && Carbon::hasFormat($date_last_contribution, 'Y-m-d'))) {
                Session::flash('message', 'Verifique la fecha de entrada y último periodo de aporte del afiliado existan antes de continuar');
                return redirect('ret_fun/' . $ret_fun_id);
            }
            if ($date_last_contribution < $date_entry) {
                Session::flash('message', 'Verifique la fecha de entrada y último periodo de aporte del afiliado estén correctas antes de continuar');
                return redirect('ret_fun/' . $ret_fun_id);
            }
            $start_date = $date_entry;
            $end_date = $date_last_contribution;
        } elseif ($ret_fun_index == 1) {
            if (!(Carbon::hasFormat($date_entry_reinstatement, 'Y-m-d') && Carbon::hasFormat($date_last_contribution_reinstatement, 'Y-m-d'))) {
                Session::flash('message', 'Verifique la fecha de entrada y último periodo de aporte de la reincorporación del afiliado existan antes de continuar');
                return redirect('ret_fun/' . $ret_fun_id);
            }
            if ($end_date < $start_date) {
                Session::flash('message', 'Verifique la fecha de entrada y último periodo de aporte de la reincorporación del afiliado estén correctas antes de continuar');
                return redirect('ret_fun/' . $ret_fun_id);
            }
            $start_date = $date_entry_reinstatement;
            $end_date = $date_last_contribution_reinstatement;
        }

        $contributions = $affiliate->contributions()->select('id', 'month_year','retirement_fund', 'total', 'breakdown_id', 'contribution_type_id')->whereBetween('month_year', [$start_date, $end_date])->orderby('month_year')->get();

        $first_contribution = Util::parseMonthYearDate(Carbon::parse($contributions->first()->month_year)->format('m/Y'));
        $last_contribution = Util::parseMonthYearDate(Carbon::parse($contributions->last()->month_year)->format('m/Y'));

        /* first contribution and date entry comparision */
        if ($start_date < $first_contribution) {
            $month = Carbon::parse($start_date);
            $months = array();
            while($month < $first_contribution){
                array_push($months, [
                    'user_id' => Auth::user()->id,
                    'affiliate_id' => $affiliate->id,
                    'type' => 'Planilla',
                    'base_wage' =>0,
                    'month_year' => $month->toDateString(),
                    'seniority_bonus' => 0,
                    'study_bonus' => 0,
                    'position_bonus' => 0,
                    'border_bonus' => 0,
                    'east_bonus' => 0,
                    'gain' => 0,
                    'quotable' => 0,
                    'retirement_fund' => 0,
                    'mortuary_quota' => 0,
                    'total' => 0,
                    'contribution_type_id' => null,
                    'category_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    ]
                );
                $month->addMonth();
            }
            DB::table('contributions')->insert($months);
        }elseif($start_date > $first_contribution){
            $month = Carbon::parse($start_date);
            $temp_ids = array();
            foreach ($contributions as $value) {
                if(Util::parseMonthYearDate(Carbon::parse($value->month_year)->format('m/Y')) < $start_date ){
                    array_push($temp_ids, $value->id);
                }
            }
            // DB::table('contributions')->where('affiliate_id', $affiliate->id)->whereIn('id', $temp_ids)->delete();
            dd("error: Se eliminaran Varias contribuciones porque las fechas no coinciden1.");
        }

        /* last contributions and date derelict comparision */

        if ($end_date > $last_contribution) {
            $month = Carbon::parse($end_date);
            $months = array();
            while($month->toDateString() > $last_contribution){
                array_push($months, [
                    'user_id' => Auth::user()->id,
                    'affiliate_id' => $affiliate->id,
                    'type' => 'Planilla',
                    'base_wage' =>0,
                    'month_year' => $month->toDateString(),
                    'seniority_bonus' => 0,
                    'study_bonus' => 0,
                    'position_bonus' => 0,
                    'border_bonus' => 0,
                    'east_bonus' => 0,
                    'gain' => 0,
                    'quotable' => 0,
                    'retirement_fund' => 0,
                    'mortuary_quota' => 0,
                    'total' => 0,
                    'contribution_type_id' => null,
                    'category_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    ]
                );
                $month->subMonth();
            }
            DB::table('contributions')->insert($months);
        }elseif($end_date < $last_contribution){
            $month = Carbon::parse($end_date);
            $temp_ids = array();
            foreach ($contributions->reverse() as $value) {
                if(Util::parseMonthYearDate(Carbon::parse($value->month_year)->format('m/Y')) > $end_date ){
                    array_push($temp_ids, $value->id);
                }
            }
            dd("error: Se eliminaran Varias contribuciones porque las fechas no coinciden.2");
            // DB::table('contributions')->where('affiliate_id', $affiliate->id)->whereIn('id', $temp_ids)->delete();
        }

        $contributions = $affiliate->contributions()->select('id', 'month_year')->whereBetween('month_year', [$start_date, $end_date])->orderby('month_year')->get()->pluck('month_year');
        $months = array();
        if(sizeof($contributions) != (1 + Carbon::parse($contributions->last())->diffInMonths(Carbon::parse($contributions->first()))) ){
            for($month = Carbon::parse($contributions->first()); $month<=$contributions->last(); $month=Carbon::parse($month)->addMonth()){
                if (in_array($month->toDateString(), $contributions->toArray())) {
                }else{
                    array_push($months, [
                        'user_id' => Auth::user()->id,
                        'affiliate_id' => $affiliate->id,
                        'type' => 'Planilla',
                        'base_wage' =>0,
                        'month_year' => $month->toDateString(),
                        'seniority_bonus' => 0,
                        'study_bonus' => 0,
                        'position_bonus' => 0,
                        'border_bonus' => 0,
                        'east_bonus' => 0,
                        'gain' => 0,
                        'quotable' => 0,
                        'retirement_fund' => 0,
                        'mortuary_quota' => 0,
                        'total' => 0,
                        'contribution_type_id' => null,
                        'category_id' => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        ]
                    );
                }
            }
            DB::table('contributions')->insert($months);
        }

        // Obtener los aportes dependiento de cuantos trámites de fondo de retiro tiene
        $contributions = $affiliate->contributions()->select('id', 'month_year','retirement_fund', 'total', 'breakdown_id', 'contribution_type_id')->whereBetween('month_year', [$start_date, $end_date])->orderbyDesc('month_year')->get();

        foreach ($contributions as $c) {
            $c->contribution_type_id = Util::classificationContribution($c->contribution_type_id, $c->breakdown_id, $c->total);
        }
        
        $contribution_types = ContributionType::select('id', 'name', 'operator')->orderBy('id')->get();
       
        if($start_date && $end_date){
            $data =   array('contributions' => $contributions,
                            'contribution_types'=> $contribution_types,
                            'date_entry' => $start_date,
                            'date_last_contribution' => $end_date,
                            'ret_fun'=>$ret_fun);
            return view('contribution.select',$data);
        }
        else{
            Session::flash('message','Verifique la fecha de entrada y desvinculacion del afiliado antes de continuar');
            return redirect('ret_fun/'.$ret_fun_id);
        }
    }
    public function saveContributions(Request $request)
    {
        // return $request->all();
        $request_contributions = collect($request->contributions);
        $ret_fun = RetirementFund::find($request->ret_fun_id);
        $ret_fun_index = $ret_fun->procedureIndex();
        $affiliate = $ret_fun->affiliate;
        $aff_contributions = $affiliate->contributionsInRange($ret_fun_index == 1);
        DB::transaction(function () use ($request_contributions, $ret_fun, $affiliate, $aff_contributions) {

            // Actualizamos el tipo de contribución
            $affiliateContributions = $aff_contributions->orderBy('month_year')->get();
            $RequestContributionsById = $request_contributions->keyBy('id');
            foreach ($affiliateContributions as $contribution) {
                if ($RequestContributionsById->has($contribution->id)) {
                    $contribution->contribution_type_id = $RequestContributionsById->get($contribution->id)['contribution_type_id'];
                    $contribution->save();
                }
            }

            $availability = $affiliate->getContributionsAvailability();
            $subtotal_availability = array_sum(array_column($availability, 'retirement_fund'));
            $ret_fun->subtotal_availability = $subtotal_availability;
            $ret_fun->save();
        });
        $contribution_types_ids = $affiliate->contributionsInRange($ret_fun_index == 1)->select('contribution_type_id')->distinct()->pluck('contribution_type_id');
        $contribution_types = ContributionType::whereIn('id',$contribution_types_ids)->orderBy('sequence')->select('name','id')->get();
        Util::getNextAreaCode($ret_fun->id);
        foreach($contribution_types as $index =>$c){
            switch ($c->id) {
                case 2:
                case 3:
                    $c['message'] = $ret_fun->contribution_types()->where('contribution_type_id', 2)->first()->pivot->message ?? null;
                    break;
                case 4:
                case 5:
                    $c['message'] = $ret_fun->contribution_types()->where('contribution_type_id', 4)->first()->pivot->message ?? null;
                    break;
                case 7:
                case 8:
                case 9:
                    $c['message'] = $ret_fun->contribution_types()->where('contribution_type_id', 7)->first()->pivot->message ?? null;
                    break;
                case 1:
                    $c['message'] = $ret_fun->contribution_types()->where('contribution_type_id', 1)->first()->pivot->message ?? null;
                    break;
                case 10:
                    $c['message'] = $ret_fun->contribution_types()->where('contribution_type_id', 10)->first()->pivot->message ?? null;
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response()->json([
            'contribution_types' => $contribution_types,
        ]);
    }
    public function printCertification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $ret_fun_index = $retirement_fund->procedureindex();
        $affiliate = $retirement_fund->affiliate;
        $servicio = ContributionType::where('name','=','Servicio Activo')->first();
        $item_cero = ContributionType::where('name','=','Período en item 0 Con Aporte')->first();
        $quantity = Util::getRetFunCurrentProcedure()->contributions_number;
        $contributions_sixty = $affiliate->contributionsInRange($ret_fun_index == 1)
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
        //$number = $retirement_fund->code;
        $number = Util::getNextAreaCode($retirement_fund->id);
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
        return \PDF::loadView('contribution.print.certification_contribution', compact('num','subtitle','place','retirement_fund','reimbursements','dateac','exp','degree','contributions','affiliate','title', 'username','institution', 'direction', 'unit', 'date', 'header', 'number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    public function printCertificationAvailability($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $ret_fun_index = $retirement_fund->procedureindex();
        $affiliate = $retirement_fund->affiliate;
        $disponibilidad = ContributionType::where('name','=','Disponibilidad')->first();
        $contributions = $affiliate->contributionsInRange($ret_fun_index == 1)
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
        $aporte=$retirement_fund->subtotal_availability;
       
        return \PDF::loadView('contribution.print.certification_availability', compact('num','disponibilidad','aporte','subtitle','place','retirement_fund','reimbursements','dateac','exp','degree','contributions','affiliate','title', 'username','institution', 'direction', 'unit', 'date','header', 'number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    public function printCertificationItem0($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $ret_fun_index = $retirement_fund->procedureindex();
        $itemcero = ContributionType::where('name','=','Período en item 0 Con Aporte')->first();
        $itemcero_sin_aporte = ContributionType::where('name','=','Período en item 0 Sin Aporte')->first();
        $contributions = $affiliate->contributionsInRange($ret_fun_index == 1)
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
        return \PDF::loadView('contribution.print.certification_item0', compact('itemcero','itemcero_sin_aporte','subtitle','place','retirement_fund','reimbursements','dateac','exp','degree','contributions','affiliate','title', 'username','institution', 'direction', 'unit', 'date','header', 'number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    //quota aid  select contributions
    public function selectContributionsQuotaAid($ret_fun_id)
    {
        $ret_fun = QuotaAidMortuary::find($ret_fun_id);
        $affiliate = $ret_fun->affiliate;
        $type_mortuary = $ret_fun->getTypeMortuary();
        if ($ret_fun->procedure_modality_id == 14) { // fallecimiento conyugue
            Session::flash('message', 'La modalidad Fallecimiento del (la) cónyuge, no requiere clasificación de aportes.');
            return redirect('quota_aid/' . $ret_fun_id);
        }
        if($ret_fun->isQuota()){
            if($ret_fun->procedure_modality_id == 8 || $ret_fun->procedure_modality_id == 9) {
                if (!(isset($affiliate->date_entry) && isset($affiliate->date_death))) {
                    Session::flash('message', 'Verifique la fecha de entrada y la fecha de fallecimiento del afiliado existan antes de continuar');
                    return redirect('quota_aid/' . $ret_fun_id);
                }
                $date_min = $affiliate->date_entry;
                $date_max = Carbon::parse(Util::parseBarDate($affiliate->date_death))->format('m/Y');
            } else {
                if (!(isset($affiliate->date_entry) && isset($affiliate->spouse[0]->date_death))) {
                    Session::flash('message', 'Verifique la fecha de entrada del titular y la fecha de fallecimiento del (la) cónyuge existan antes de continuar');
                    return redirect('quota_aid/' . $ret_fun_id);
                }
                $date_min = $affiliate->date_entry;
                $date_max = Carbon::parse(Util::parseBarDate($affiliate->spouse[0]->date_death))->format('m/Y');
            }
            if (!(isset($date_min) && isset($date_max))) {
                Session::flash('message', 'Verifique la fecha de entrada y fecha de fallecimiento del afiliado existan antes de continuar');
                return redirect('quota_aid/' . $ret_fun_id);
            }
            $min_limit = Util::parseMonthYearDate($date_min);
            $max_limit = Util::parseMonthYearDate($date_max);
            if ($max_limit < $min_limit) {
                Session::flash('message', 'Verifique la fecha de ingreso '.$min_limit.' y fecha de fallecimiento '.$max_limit.' del titular estén correctas antes de continuar');
                return redirect('quota_aid/' . $ret_fun_id);
            }
        }else{
            if($ret_fun->procedure_modality_id == 15){//fallecimiento viuda
                if (!(isset($affiliate->date_death) && isset($affiliate->spouse[0]->date_death))) {
                    Session::flash('message', 'Verifique la fecha de fallecimiento del afiliado y fecha de fallecimiento de la viuda existan antes de continuar');
                    return redirect('quota_aid/' . $ret_fun_id);
                }
                $date_min = Carbon::parse(Util::parseBarDate($affiliate->date_death))->format('m/Y');
                $date_max = Carbon::parse(Util::parseBarDate($affiliate->spouse[0]->date_death))->format('m/Y');
                if (!(isset($date_min) && isset($date_max))) {
                    Session::flash('message', 'Verifique la fecha de fallecimiento del titular y fecha de fallecimiento de la viuda(o) existan antes de continuar');
                    return redirect('quota_aid/' . $ret_fun_id);
                }
                $min_limit = Util::parseMonthYearDate($date_min);
                $max_limit = Util::parseMonthYearDate($date_max);
                if ($max_limit < $min_limit) {
                    Session::flash('message', 'Verifique la fecha de fallecimiento titular '.$min_limit.' y fecha de fallecimiento viuda '.$max_limit.' estén correctas antes de continuar');
                    return redirect('quota_aid/' . $ret_fun_id);
                }
            }else{

                if (!(isset($affiliate->date_last_contribution) && isset($affiliate->date_death))) {
                    Session::flash('message', 'Verifique la fecha de último periodo de aporte  y fecha de fallecimiento del afiliado existan antes de continuar');
                    return redirect('quota_aid/' . $ret_fun_id);
                }

                $date_min = $affiliate->date_last_contribution;
                $date_max = Carbon::parse(Util::parseBarDate($affiliate->date_death))->format('m/Y');

                if (!(isset($date_min) && isset($date_max))) {
                    Session::flash('message', 'Verifique la fecha de último periodo de aporte y fecha de fallecimiento del afiliado existan antes de continuar');
                    return redirect('quota_aid/' . $ret_fun_id);
                }
                $min_limit = Util::parseMonthYearDate($date_min);
                $max_limit = Util::parseMonthYearDate($date_max);
                if ($max_limit < $min_limit) {
                    Session::flash('message', 'Verifique la fecha de último periodo de aporte '.$min_limit.' y fecha de fallecimiento '.$max_limit.'  del afiliado estén correctas antes de continuar');
                    return redirect('quota_aid/' . $ret_fun_id);
                }
            }
        }
        if($ret_fun->isQuota()){
            $contributions = $affiliate->contributions()->orderBy('month_year')->get();
        }else{
            $contributions = $affiliate->aid_contributions()->where('month_year','>=',$min_limit)->where('month_year','<',$max_limit)->orderBy('month_year')->get();
        }

        //auxilio
        if (count($contributions)<1 && !$ret_fun->isQuota()) {
             $month = Carbon::parse($min_limit);
             $months = array();
             while($month < Carbon::parse($max_limit)->subMonth()){
                array_push($months, [
                    'user_id' => Auth::user()->id,
                    'affiliate_id' => $affiliate->id,
                    'month_year' => $month->toDateString(),
                    'contribution_state_id'=>2,
                    'total' => 0,
                    'affiliate_rent_class'=> $ret_fun->procedure_modality_id == 15? 'VIUDEDAD':'VEJEZ',
                    'contribution_type_mortuary_id' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
            $month->addMonth();
            }
            DB::table('contribution_passives')->insert($months);
            $contributions = $affiliate->aid_contributions()->where('month_year','>=',$min_limit)->where('month_year','<',$max_limit)->orderBy('month_year')->get();
        }

        if (count($contributions)<1) {
            Session::flash('message', 'Verifique que tenga aportes');
            return redirect('quota_aid/' . $ret_fun_id);
        }

        $first_contribution = Util::parseMonthYearDate(Carbon::parse($contributions->first()->month_year)->format('m/Y'));
        $last_contribution = Util::parseMonthYearDate(Carbon::parse($contributions->last()->month_year)->format('m/Y'));
        if (Carbon::parse($max_limit)->subMonth() > $last_contribution) {
            $month =  Carbon::parse($max_limit)->subMonth();
            $months = array();
            while($month->toDateString() > $last_contribution){
                if($ret_fun->isQuota()){
                    array_push($months, [
                        'user_id' => Auth::user()->id,
                        'affiliate_id' => $affiliate->id,
                        'type' => 'Planilla',
                        'base_wage' =>0,
                        'month_year' => $month->toDateString(),
                        'seniority_bonus' => 0,
                        'study_bonus' => 0,
                        'position_bonus' => 0,
                        'border_bonus' => 0,
                        'east_bonus' => 0,
                        'gain' => 0,
                        'quotable' => 0,
                        'retirement_fund' => 0,
                        'mortuary_quota' => 0,
                        'total' => 0,
                        'contribution_type_mortuary_id' => null,
                        'category_id' => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        ]
                    );
                    $month->subMonth();
                }else{
                 //   dd('entra aqui en caso 1');
                    array_push($months, [
                        'user_id' => Auth::user()->id,
                        'affiliate_id' => $affiliate->id,
                        'month_year' => $month->toDateString(),
                        'contribution_state_id'=>2,
                        'total' => 0,
                        'affiliate_rent_class'=> $ret_fun->procedure_modality_id == 15? 'VIUDEDAD':'VEJEZ',
                        'contribution_type_mortuary_id' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        ]
                    );
                    $month->subMonth();
                }
            }
            if($ret_fun->isQuota())
                DB::table('contributions')->insert($months);
            else
                DB::table('contribution_passives')->insert($months);
        }
        // elseif($max_limit < $last_contribution){
        //     $month = Carbon::parse($max_limit);
        //     $temp_ids = array();
        //     foreach ($contributions->reverse() as $value) {
        //         if(Util::parseMonthYearDate(Carbon::parse($value->month_year)->format('m/Y')) > $date_max){
        //             array_push($temp_ids, $value->id);
        //         }
        //     }
        //     dd("error: Se eliminaran Varias contribuciones porque las fechas no coinciden. ".$max_limit .' < '. $last_contribution);
        //     // DB::table('contributions')->where('affiliate_id', $affiliate->id)->whereIn('id', $temp_ids)->delete();
        // }
         /*Comparación de entre primer aporte y limite inferior*/
         if ($min_limit < $first_contribution) {
             $month = Carbon::parse($min_limit);
             $months = array();
             while($month < $first_contribution){
                 if($ret_fun->isQuota()){
                     array_push($months, [
                         'user_id' => Auth::user()->id,
                         'affiliate_id' => $affiliate->id,
                         'type' => 'Planilla',
                         'base_wage' =>0,
                         'month_year' => $month->toDateString(),
                         'seniority_bonus' => 0,
                         'study_bonus' => 0,
                         'position_bonus' => 0,
                         'border_bonus' => 0,
                         'east_bonus' => 0,
                         'gain' => 0,
                         'quotable' => 0,
                         'retirement_fund' => 0,
                         'mortuary_quota' => 0,
                         'total' => 0,
                         'contribution_type_mortuary_id' => null,
                         'category_id' => 1,
                         'created_at' => Carbon::now(),
                         'updated_at' => Carbon::now(),
                         ]
                     );
                     $month->addMonth();
                 }else{
                     array_push($months, [
                         'user_id' => Auth::user()->id,
                         'affiliate_id' => $affiliate->id,
                         'month_year' => $month->toDateString(),
                         'contribution_state_id'=>2,
                         'total' => 0,
                         'affiliate_rent_class'=> $ret_fun->procedure_modality_id == 15? 'VIUDEDAD':'VEJEZ',
                         'contribution_type_mortuary_id' => null,
                         'created_at' => Carbon::now(),
                         'updated_at' => Carbon::now(),
                         ]
                     );
                     $month->addMonth();
                 }
             }
             if($ret_fun->isQuota())
                 DB::table('contributions')->insert($months);
             else
                 DB::table('contribution_passives')->insert($months);
         }elseif( $min_limit > $first_contribution){
             $month = Carbon::parse($min_limit);
             $temp_ids = array();
             foreach ($contributions as $value) {
                 if(Util::parseMonthYearDate(Carbon::parse($value->month_year)->format('m/Y')) < $date_min ){
                     array_push($temp_ids, $value->id);
                 }
             }
             // DB::table('contributions')->where('affiliate_id', $affiliate->id)->whereIn('id', $temp_ids)->delete();
             dd("error: Se eliminaran Varias contribuciones porque las fechas no coinciden.3");
        }
        if($ret_fun->isQuota()){
            $contributions = $affiliate->contributions()->where('month_year','<',"$max_limit")->orderBy('month_year')->get()->pluck('month_year');;
        }else{
            $contributions = $affiliate->aid_contributions()->where('month_year','>=',"$min_limit")->where('month_year','<',"$max_limit")->orderBy('month_year')->get()->pluck('month_year');;
        }
        $months = array();
        if(sizeof($contributions) != (1 + Carbon::parse($contributions->last())->diffInMonths(Carbon::parse($contributions->first()))) ){
            for($month = Carbon::parse($contributions->first()); $month<=$contributions->last(); $month=Carbon::parse($month)->addMonth()){
                if (in_array($month->toDateString(), $contributions->toArray())) {
                }else{
                    if($ret_fun->isQuota()){
                        array_push($months, [
                            'user_id' => Auth::user()->id,
                            'affiliate_id' => $affiliate->id,
                            'type' => 'Planilla',
                            'base_wage' =>0,
                            'month_year' => $month->toDateString(),
                            'seniority_bonus' => 0,
                            'study_bonus' => 0,
                            'position_bonus' => 0,
                            'border_bonus' => 0,
                            'east_bonus' => 0,
                            'gain' => 0,
                            'quotable' => 0,
                            'retirement_fund' => 0,
                            'mortuary_quota' => 0,
                            'total' => 0,
                            'contribution_type_mortuary_id' => null,
                            'category_id' => 1,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            ]
                        );
                    }else{
                        array_push($months, [
                            'user_id' => Auth::user()->id,
                            'affiliate_id' => $affiliate->id,
                            'month_year' => $month->toDateString(),
                            'contribution_state_id'=>2,
                            'total' => 0,
                            'affiliate_rent_class'=> $ret_fun->procedure_modality_id == 15? 'VIUDEDAD':'VEJEZ',
                            'contribution_type_mortuary_id' => null,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            ]
                        );
                    }
                }
            }
            if($ret_fun->isQuota())
                DB::table('contributions')->insert($months);
            else
                DB::table('contribution_passives')->insert($months);
        }

        if($ret_fun->isQuota())
            $contributions = $affiliate->contributions()->select('id', 'month_year','retirement_fund','mortuary_quota', 'total', 'breakdown_id', 'contribution_type_id','contribution_type_mortuary_id')->where('month_year','<',"$max_limit")->orderbyDesc('month_year')->get();
        else{
            $contributions = $affiliate->aid_contributions()->select('id', 'month_year', 'total','contribution_type_mortuary_id')->where('month_year','>=',"$min_limit")->where('month_year','<',"$max_limit")->orderbyDesc('month_year')->get();
            if($ret_fun->procedure_modality_id == 15){
                $affiliate->aid_contributions()->select('id', 'month_year', 'total','contribution_type_mortuary_id')->where('month_year','>=',"$min_limit")->where('month_year','<',"$max_limit")->where('affiliate_rent_class','VEJEZ')->update(['affiliate_rent_class' => 'VEJEZ/VIUDEDAD']);
            }
        }

        $contribution_types = ContributionTypeQuotaAid::select('id', 'name')->orderBy('id')->get();
        $date_entry = $date_min;
        $date_last_contribution =$date_max;

        if($date_min){
            $data =   array('contributions' => $contributions,
                            'contribution_types'=> $contribution_types,
                            'date_entry' => Util::parseMonthYearDate($date_entry),
                            'date_last_contribution' => Util::parseMonthYearDate($date_last_contribution),
                            'ret_fun'=>$ret_fun);
            return view('contribution.selectQuotaAid',$data);
        }
        else{
            Session::flash('message','Verifique la fecha de entrada y fallecimiento del afiliado antes de continuar');
            return redirect('quota_aid/'.$ret_fun_id);
        }
    }
    //guardar contribuciones
    public function saveContributionsQuotaAid(Request $request){
        $request_contributions = $request->contributions;
        $ret_fun = QuotaAidMortuary::find($request->ret_fun_id);
        $affiliate = $ret_fun->affiliate;
        if($ret_fun->isQuota())
            $contributions = $affiliate->contributions()->orderBy('month_year')->get();
        else
            $contributions = $affiliate->aid_contributions()->orderBy('month_year')->get();
        foreach ($contributions as $c) {
            foreach($request_contributions as $rc){
                if($rc['id'] == $c->id){
                    $c->contribution_type_mortuary_id = $rc['contribution_type_mortuary_id'];
                    $c->save();
                }
            }
        }
        $count_contributions = 0;
        $grace_period = true;
        $count_grace = 0;
        if($ret_fun->isQuota()){
            if($ret_fun->procedure_modality_id == 8){//en cumplimiento de funciones
                foreach($request_contributions as $index =>$cont){
                    if($cont['contribution_type_mortuary_id'] == 1){
                        $count_contributions++;
                    }
                }
                if($count_contributions >= 12){ // debe tener si o si al menos 12 para acceder al beneficio
                    $procedure = QuotaAidProcedure::where('hierarchy_id', $affiliate->degree->hierarchy_id)->where('procedure_modality_id', $ret_fun->procedure_modality_id)->where('is_enabled',true)->select('id')->first();
                    $ret_fun->quota_aid_procedure_id = $procedure->id;
                    $ret_fun->number_qualified_contributions = $count_contributions;
                    $ret_fun->save();
                }else{
                    $ret_fun->quota_aid_procedure_id = null;
                    $ret_fun->number_qualified_contributions = $count_contributions;
                    $ret_fun->save();
                }
            }else{// por riesgo comun
                foreach($request_contributions as $index =>$cont){
                    if($cont['contribution_type_mortuary_id'] == 1){
                        $count_contributions++;
                    }
                }
                if($count_contributions >= 12){ // debe tener si o si al menos 12 para acceder al beneficio
                    $contributions = $affiliate->contributions()->where('contribution_type_mortuary_id',1)->orderBy('month_year')->get();
                    $count_contributions = count($contributions);
                    $procedure = QuotaAidProcedure::where('hierarchy_id', $affiliate->degree->hierarchy_id)->where('procedure_modality_id', $ret_fun->procedure_modality_id)->where('is_enabled',true)->where('months_min','<=', $count_contributions)->where('months_max','>=', $count_contributions)->select('id')->first();
                    $ret_fun->quota_aid_procedure_id = $procedure->id;
                    $ret_fun->number_qualified_contributions = $count_contributions;
                    $ret_fun->save();
                }else{
                    $ret_fun->quota_aid_procedure_id = null;
                    $ret_fun->number_qualified_contributions = $count_contributions;
                    $ret_fun->save();
                }
            }
        }else{//auxilio mortuorio
            foreach($request_contributions as $index =>$cont){
                //if($cont['contribution_type_mortuary_id'] == 1 && $grace_period == true){
                //     if($cont['contribution_type_mortuary_id'] == 1){
                //         $count_contributions++;
                //     }else
                //         break;
                // }else{
                //     $count_grace++;
                //     if($count_grace>4)
                //         $grace_period = false;
                // }
                if($cont['contribution_type_mortuary_id'] == 1){
                    $count_contributions++;
                }

            }

            if($count_contributions > 0){ // debe tener si o si al menos 1 para acceder al beneficio
                $procedure = QuotaAidProcedure::where('hierarchy_id', $affiliate->degree->hierarchy_id)->where('procedure_modality_id', $ret_fun->procedure_modality_id)->where('is_enabled',true)->where('months_min','<=', $count_contributions)->where('months_max','>=', $count_contributions)->select('id')->first();
                $ret_fun->quota_aid_procedure_id = $procedure->id;
                $ret_fun->number_qualified_contributions = $count_contributions;
                $ret_fun->save();
            }else{
                $count_contributions = 1;// aquellos que firman carta de compromiso de pago
                $procedure = QuotaAidProcedure::where('hierarchy_id', $affiliate->degree->hierarchy_id)->where('procedure_modality_id', $ret_fun->procedure_modality_id)->where('is_enabled',true)->where('months_min','<=', $count_contributions)->where('months_max','>=', $count_contributions)->select('id')->first();
                $ret_fun->quota_aid_procedure_id = $procedure->id;
                $ret_fun->number_qualified_contributions = $count_contributions;
                $ret_fun->save();
            }
        }
        $contribution_types = ContributionTypeQuotaAid::whereIn('id',$ret_fun->affiliate->contributions()->select('contribution_type_mortuary_id')->distinct()->get()->pluck('contribution_type_mortuary_id'))->orderBy('sequence')->select('name','id')->get();
       // Util::getNextAreaCode($ret_fun->id);
        foreach($contribution_types as $index =>$c){
            switch ($c->id) {
                case 2:
                    //$c['message'] = $ret_fun->contribution_types()->where('contribution_type_id', 2)->first()->pivot->message ?? null;
                    break;
                case 1:
                   // $c['message'] = $ret_fun->contribution_types()->where('contribution_type_id', 1)->first()->pivot->message ?? null;
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response()->json([
            'contribution_types' => $contribution_types,
        ]);
    }
}