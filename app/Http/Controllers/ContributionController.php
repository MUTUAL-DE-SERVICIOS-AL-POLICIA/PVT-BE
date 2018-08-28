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
use DateInterval;
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
    private function getMonthContributions($id){    
        $today = date('Y-m-d');//'2018-05-01';//date('Y-m-d');
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
       
        $result = [];
        $stored_contributions = [];        
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
                $contribution->breakdown_id = 3;
                $contribution->save();
            }

            array_push($result, [
                'total'=>$contribution->total,
                'month_year'=>$aporte->year.'-'.$aporte->month.'-01',
                    ]);
            array_push($stored_contributions,$contribution);            
        }
        
        $data = [
            'contribution'  =>  $result,
            'contributions'  =>  $stored_contributions,
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
                
    }
    public function directContributions(Affiliate $affiliate = null){

         $commitment = ContributionCommitment::where('affiliate_id',$affiliate->id)->where('state','ALTA')->first();                                            
         if(!isset($commitment->id) && Util::getRol()->pivot->role_id != 12)
        {            
            Session::flash('message','No se encontró compromiso de pago');
            return redirect('affiliate/'.$affiliate->id);    
        }
        if(!isset($commitment->id))
        {
            $commitment = new ContributionCommitment();
            $commitment->id = 0;
            $commitment->affiliate_id = $affiliate->id;
        }
        
        $contributions = Contribution::where('affiliate_id', $affiliate->id)->orderBy('month_year', 'DESC')->get();        
        $last_contribution = Contribution::where('affiliate_id',$affiliate->id)->orderBy('month_year','desc')->first();
        $rate = ContributionRate::where('month_year',date('Y').'-'.date('m').'-01')->first();
        
        $summary = array(
           'fondoret' => $contributions->sum('retirement_fund'),
           'quotaaid' => $contributions->sum('mortuary_quota'),
           'total' => $contributions->sum('total'),
           'interest'  =>  $contributions->sum('interest'),
           'dateentry' => Util::getStringDate(Util::parseMonthYearDate($affiliate->date_entry))
        ); 
        
        $data = [
            'new_contributions' => $this->getMonthContributions($affiliate->id),            
            'commitment'    =>  $commitment,
            'affiliate' =>  $affiliate,
            'summary'   =>  $summary,
            'last_quotable' =>  $last_contribution->quotable ?? 0,
            'today_date'    =>  date('Y-m-d'),
            'rate'  =>  $rate,        
        ];

        return view('contribution.affiliate_direct_contributions', $data);        
    }
    public function getContributionsByMonth(Affiliate $affiliate = null){
        $contributions =  Contribution::where('affiliate_id',$affiliate->id)->pluck('total','month_year')->toArray();        
        $end = explode('-', Util::parseMonthYearDate($affiliate->date_entry));
        $month_end = $end[1];
        $year_end = $end[0];
        if($affiliate->date_derelict)
            $start = explode('-', Util::parseMonthYearDate($affiliate->date_derelict));  
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
        $date_derelict = $affiliate->date_derelict;     
        if(!$affiliate->date_derelict)
            $date_derelict  =   date('Y-m-d');
        if(!$date_entry || !$date_derelict){
            Session::flash('message','Verifique la fecha de entrada y desvinculación del afiliado antes de continuar');
            return redirect('affiliate/'.$affiliate->id);
        }
        
        $contributions = Contribution::where('affiliate_id', $affiliate->id)->orderBy('month_year', 'DESC')->get();
        
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
        
        $start = explode('-', Util::parseMonthYearDate($affiliate->date_derelict));      
        if(!$affiliate->date_derelict)
        $start = explode('-', date('Y-m-d'));              
        $month_start = $start[1];
        $year_start = $start[0];                
        $commitment = ContributionCommitment::where('affiliate_id',$affiliate->id)->where('state','ALTA')->first();        
        if(!isset($commitment->id))
        {
            $commitment = new ContributionCommitment();
            $commitment->id = 0;
            $commitment->affiliate_id = $affiliate->id;
        }
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
            'commitment'    =>  $commitment,
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
                    //$category = Category::find($request->category[$key]);
                    $category = Category::where('percentage',$request->category[$key])->first();
                    $contribution->category_id = $category->id;
                    //return $category->percentage." ".$contribution->base_wage;
                    //$contribution->seniority_bonus = $category->percentage * $contribution->base_wage;
                   $contribution->seniority_bonus = $request->seniority_bonus[$key];
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
                
                if(!isset($request->base_wage[$key]))
                    $contribution->base_wage = 0;
                else
                    $contribution->base_wage = strip_tags($request->base_wage[$key]) ?? 0;

                $category = Category::where('percentage',$request->category[$key])->first();
                if(!isset($category->id)) {
                    $contribution->category_id = 11; //default non category found -0
                } else {
                    $contribution->category_id = $category->id;
                }                    
                //$data = $contribution->base_wage * 123;                
                $contribution->seniority_bonus = $request->seniority_bonus[$key] ?? 0;
                $contribution->study_bonus = 0;
                $contribution->position_bonus = 0;
                $contribution->border_bonus = 0;
                $contribution->east_bonus = 0;
                $contribution->quotable = 0;
                $contribution->month_year = $key;

                if(!isset($request->gain[$key]))
                    $contribution->gain = 0;
                else
                    $contribution->gain = strip_tags($request->gain[$key]) ?? 0;
                $contribution->retirement_fund = 0;
                $contribution->mortuary_quota = 0;
                $contribution->total = strip_tags($request->total[$key]) ?? 0;                
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
        $ret_fun = RetirementFund::find($ret_fun_id);
        $affiliate = $ret_fun->affiliate;

        if (!(isset($affiliate->date_entry) && isset($affiliate->date_derelict))) {
            Session::flash('message', 'Verifique la fecha de entrada y desvinculación del afiliado existan antes de continuar');
            return redirect('ret_fun/' . $ret_fun_id);
        }
        if (Util::parseMonthYearDate($affiliate->date_derelict) < Util::parseMonthYearDate($affiliate->date_entry)) {
            Session::flash('message', 'Verifique la fecha de entrada y desvinculación del afiliado estén correctas antes de continuar');
            return redirect('ret_fun/' . $ret_fun_id);
        }

        $contributions = $affiliate->contributions()->orderBy('month_year')->get();
        $first_contribution = Util::parseMonthYearDate(Carbon::parse($contributions->first()->month_year)->format('m/Y'));
        $last_contribution = Util::parseMonthYearDate(Carbon::parse($contributions->last()->month_year)->format('m/Y'));

        /* first contribution and date entry comparision */
        if (Util::parseMonthYearDate($affiliate->date_entry) < $first_contribution) {
            $month = Carbon::parse(Util::parseMonthYearDate($affiliate->date_entry));
            Log::info("++++++++++++ creating ++++++++++++");
            Log::info($month);
            Log::info($first_contribution);
            $months = array();
            while($month < $first_contribution){
                Log::info('pending create: '.$month);
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
            Log::info("************ / end creating********");
        }elseif(Util::parseMonthYearDate($affiliate->date_entry) > $first_contribution){
            $month = Carbon::parse(Util::parseMonthYearDate($affiliate->date_entry));
            Log::info("-------------  deleting ---------");
            Log::info($month);
            Log::info($first_contribution);
            $temp_ids = array();
            foreach ($contributions as $value) {
                if(Util::parseMonthYearDate(Carbon::parse($value->month_year)->format('m/Y')) < Util::parseMonthYearDate($affiliate->date_entry) ){
                    Log::info('deleted '.$value->month_year);
                    array_push($temp_ids, $value->id);
                }
            }
            // DB::table('contributions')->where('affiliate_id', $affiliate->id)->whereIn('id', $temp_ids)->delete();
            dd("error: Se eliminaran Varias contribuciones porque no las fechas no coinciden.");
            Log::info("-------------  / end deleting ---------");
        }

        /* last contributions and date derelict comparision */

        if (Util::parseMonthYearDate($affiliate->date_derelict) > $last_contribution) {
            $month = Carbon::parse(Util::parseMonthYearDate($affiliate->date_derelict));
            Log::info("++++++++++++ creating ++++++++++++");
            Log::info($month);
            Log::info($last_contribution);
            $months = array();
            while($month->toDateString() > $last_contribution){
                Log::info('pending create: '.$month);
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
            Log::info("************ / end creating********");
        }elseif(Util::parseMonthYearDate($affiliate->date_derelict) < $last_contribution){
            $month = Carbon::parse(Util::parseMonthYearDate($affiliate->date_derelict));
            Log::info("-------------  deleting ---------");
            Log::info($month);
            Log::info($last_contribution);
            $temp_ids = array();
            foreach ($contributions->reverse() as $value) {
                if(Util::parseMonthYearDate(Carbon::parse($value->month_year)->format('m/Y')) > Util::parseMonthYearDate($affiliate->date_derelict) ){
                    Log::info('deleted '.$value->month_year);
                    array_push($temp_ids, $value->id);
                }
            }
            dd("error: Se eliminaran Varias contribuciones porque no las fechas no coinciden.");
            // DB::table('contributions')->where('affiliate_id', $affiliate->id)->whereIn('id', $temp_ids)->delete();
            Log::info("-------------  / end deleting ---------");
        }

        $contributions = DB::table('contributions')->where('affiliate_id', $affiliate->id)->orderBy('month_year')->get()->pluck('month_year');
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


        $contributions = $affiliate->contributions()->select('id', 'month_year','retirement_fund', 'total', 'breakdown_id', 'contribution_type_id')->orderbyDesc('month_year')->get();

        foreach ($contributions as $c) {
            $c->contribution_type_id = Util::classificationContribution($c->contribution_type_id, $c->breakdown_id, $c->total);
        }
        // dd($contributions->first());
        // $con_type = false;
        // $contributions= DB::table('contributions')->join('categories','contributions.category_id','categories.id')
        //                                         ->join('contribution_types','contribution_types.id','contributions.contribution_type_id')
        //                                         ->where('contributions.affiliate_id',$ret_fun->affiliate_id)
        //                                         ->where('contributions.month_year','>=', Util::parseMonthYearDate($affiliate->date_entry))
        //                                         //   ->whereNull('contributions.deleted_at')
        //                                         ->select('contributions.id','contributions.base_wage','contributions.total','contributions.gain','contributions.retirement_fund','contributions.contribution_type_id as breakdown_id','contribution_types.name as breakdown_name','contributions.category_id','categories.name as category_name','contributions.month_year')
        //                                         //   ->take(10)
        //                                         ->orderBy('contributions.month_year', 'desc')
        //                                         ->get();
        //                                         // $contributions = [];
        //                                         //    return $contributions->count();
                                                
        // if(sizeof($contributions) == 0){
        //   $contributions= DB::table('contributions')->join('categories','contributions.category_id','categories.id')
        //                                             ->join('breakdowns','contributions.breakdown_id','breakdowns.id')
        //                                             ->where('contributions.affiliate_id',$ret_fun->affiliate_id)
        //                                             ->where('contributions.month_year','>=',Util::parseMonthYearDate($affiliate->date_entry))
        //                                             // ->whereNull('contributions.deleted_at')
        //                                             ->select('contributions.id','contributions.base_wage','contributions.total','contributions.gain','contributions.retirement_fund','contributions.breakdown_id','breakdowns.name as breakdown_name','contributions.category_id','categories.name as category_name','contributions.month_year')
        //                                         //   ->take(10)
        //                                             ->orderBy('contributions.month_year', 'desc')
        //                                             ->get();
        //    $con_type=true;
        // }  
        
        
       
        $contribution_types = ContributionType::select('id', 'name')->orderBy('id')->get();
        $date_entry = $ret_fun->affiliate->date_entry;
        $date_derelict = $ret_fun->affiliate->date_derelict;
        // return $date_derelict;
        // return $contribution_types;
        //return $contributions;
        if($date_entry && $date_derelict){
            $data =   array('contributions' => $contributions,
                            'contribution_types'=> $contribution_types,
                            'date_entry' => Util::parseMonthYearDate($date_entry),
                            'date_derelict' => Util::parseMonthYearDate($date_derelict),
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
        $request_contributions = $request->contributions;
        $ret_fun = RetirementFund::find($request->ret_fun_id);
        $affiliate = $ret_fun->affiliate;
        $contributions = $affiliate->contributions()->orderBy('month_year')->get();
        foreach ($contributions as $c) {
            foreach($request_contributions as $rc){
                if($rc['id'] == $c->id){
                    $c->contribution_type_id = $rc['contribution_type_id'];
                    $c->save();
                }
            }

        }
        $availability = $affiliate->getContributionsAvailability();
        $subtotal_availability = array_sum(array_column($availability, 'total'));
        $ret_fun->subtotal_availability = $subtotal_availability;
        $ret_fun->save();
        Log::info('saved subtotal availability: '. $subtotal_availability);
        $contribution_types = ContributionType::whereIn('id',$ret_fun->affiliate->contributions()->select('contribution_type_id')->distinct()->get()->pluck('contribution_type_id'))->orderBy('sequence')->select('name','id')->get();
        return response()->json([
            'contribution_types' => $contribution_types
        ]);
    }
    public function printCertification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $servicio = ContributionType::where('name','=','Servicio Activo')->first();
        $item_cero = ContributionType::where('name','=','Período en item 0 Con Aporte')->first();
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
        $aporte=$retirement_fund->subtotal_availability;
       
        return \PDF::loadView('contribution.print.certification_availability', compact('num','disponibilidad','aporte','subtitle','place','retirement_fund','reimbursements','dateac','exp','degree','contributions','affiliate','title', 'username','institution', 'direction', 'unit', 'date','header', 'number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printCertificationItem0($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $itemcero = ContributionType::where('name','=','Período en item 0 Con Aporte')->first();
        $itemcero_sin_aporte = ContributionType::where('name','=','Período en item 0 Sin Aporte')->first();
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
        return \PDF::loadView('contribution.print.certification_item0', compact('itemcero','itemcero_sin_aporte','subtitle','place','retirement_fund','reimbursements','dateac','exp','degree','contributions','affiliate','title', 'username','institution', 'direction', 'unit', 'date','header', 'number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    } 
}