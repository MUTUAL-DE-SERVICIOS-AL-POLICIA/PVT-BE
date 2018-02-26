<?php

namespace Muserpol\Http\Controllers;
//use Muserpol\Contribution;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Contribution\Contribution;
use Auth;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\Category;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 123123;
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
        //
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
    
    public function getAffiliateContributions(Affiliate $affiliate){        
        $contributions = Contribution::where('affiliate_id',$affiliate->id)->orderBy('month_year','DESC')->get();
        $reims = Reimbursement::where('affiliate_id',$affiliate->id)->get();
        
        $group = [];
        $group_reim = [];
        foreach ($reims as $reim)
            $group_reim[$reim->month_year] = $reim;
        foreach ($contributions as $contribution)
        {   
            $group[$contribution->month_year] = $contribution;
            
//            $index = explode("-", $contribution->month_year);            
//            $index = $index[0];
//            if(!isset($group[$index]))
//                    $group[$index] = [];
//            array_push($group[$index], $contribution);
            
            //array_pu
                    
        }        
        $categories = Category::get();
        //return $group;
        //return $affiliate->date_entry;
        $end = explode('-', $affiliate->date_entry);        
        $newcontributions = [];
        $month_end = $end[1];
        $year_end = $end[0]; 
        $month_start = (date('m')-1);
        $year_start = date('Y');
        $data = [            
            'contributions' => $group,
            'reims' =>  $group_reim,
            'affiliate_id'  =>  $affiliate->id,
            'categories'    =>  $categories,
            'monthi'    => 1,
            'year_start'    =>  $year_start,
            'year_end'  => $year_end,
        ];
        
        return view('contribution.affiliate_contributions',$data);
        
        echo $month_end." ".$year_end."-".$month_start." ".$year_start; 
        $contributions = [];
        while($month_end!=$month_start || $year_start != $year_end)  {
            $formated_date = $year_start."-".($month_start<10?"0".$month_start:$month_start)."-01";
            $contri  = Contribution::where('affiliate_id',$affiliate->id)->where('month_year',$formated_date)->first();
            if(!isset($contri->id)){
                $contri = new Contribution();
                $contri->month_year = $formated_date;
            }
            
            //echo $contri->month_year."<br>";
            array_push($contributions, $contri);
            ///echo "<br>".$month_start." ".$year_start; 
            if($month_start == 1){
                $year_start--;
                $month_start=12;
            }
            else
            {
                $month_start--;
            }
        }
        //return 0;
        
//        while ()
//        foreach ($contributions as $contribution)
//        {
//            
//            if($month == 1)
//            {
//                $year = $year-1;
//            }
//        }
//return 0;
        return view('contribution.affiliate_contributions',$data);
    }
    
        public function getAllContribution(Request $request)
    {
        
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';          
        $last_name = strtoupper($request->last_name) ?? '';
        $first_name = strtoupper($request->first_name) ?? '';
        $code = $request->code ?? '';
        $modality = strtoupper($request->modality) ?? '';
        

//        $total = RetirementFund::select('retirement_funds.id')
//                                ->leftJoin('affiliates','retirement_funds.id','=','affiliates.id')
//                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modality_id','=','procedure_modalities.id')
//                                ->leftJoin('workflows','retirement_funds.workflow_id','=','workflows.id')                               
//                                ->where('retirement_funds.code','LIKE',$code.'%')
//                                //->where('procedure_modalities.name','LIKE',$modality.'%')
//                                ->where('affiliates.first_name','LIKE',$first_name.'%')
//                                ->where('affiliates.last_name','LIKE',$last_name.'%')                                
//                                ->count();
        
         $total = 1000;                    
//        $ret_funds = RetirementFund::select('retirement_funds.id','affiliates.first_name as first_name','affiliates.last_name as last_name','procedure_modalities.name as modality','workflows.name as workflow','retirement_funds.code','retirement_funds.reception_date','retirement_funds.total')
//                                ->leftJoin('affiliates','retirement_funds.id','=','affiliates.id')
//                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modality_id','=','procedure_modalities.id')
//                                ->leftJoin('workflows','retirement_funds.workflow_id','=','workflows.id')                               
//                                ->where('affiliates.first_name','LIKE',$first_name.'%')
//                                //->where('procedure_modalities.name','LIKE',$modality.'%')
//                                ->where('affiliates.last_name','LIKE',$last_name.'%')
//                                ->where('retirement_funds.code','LIKE',$code.'%')
//                                ->skip($offset)
//                                ->take($limit)
//                                ->orderBy($sort,$order)
//                                ->get();
        $contributions = Contribution::where('affiliate_id',1)
                            ->skip($offset)
                            ->take($limit)
                            ->orderBy($sort,$order)
                            ->get();
        
        
        return response()->json(['contributions' => $contributions->toArray(),'total'=>$total]);
    }
    public function storeContributions(Request $request){
             
        foreach ($request->total as $key=>$total)
        {
            
            $contribution = Contribution::where('affiliate_id',$request->affiliate_id)->where('month_year',$key)->first();
            if(isset($contribution->id))
            {
                //$string.=$total;
                $contribution->total = $total;
                $contribution->save();
            }
            else 
            {
//                $contribution = new Contribution();
//                $contribution->user_id = Auth::user()->id;
//                $contribution->total = $total;
                
                $affiliate = Affiliate::find(1);
                $contribution = new Contribution();
                $contribution->user_id = Auth::user()->id;
                $contribution->affiliate_id = $affiliate->id;
                $contribution->degree_id = $affiliate->degree_id;
                $contribution->unit_id = $affiliate->unit_id;
                $contribution->breakdown_id = $affiliate->breakdown_id;
                $contribution->category_id = $affiliate->category_id;
                $contribution->base_wage = $request->base_wage[$key];
                $contribution->seniority_bonus = 0;
                $contribution->study_bonus = 0;
                $contribution->position_bonus = 0;
                $contribution->border_bonus = 0;
                $contribution->east_bonus = 0;
                $contribution->quotable = 0;
                $contribution->month_year = $key;
                $contribution->gain = 0;
                $contribution->retirement_fund = 0;
                $contribution->mortuary_quota = 0;
                $contribution->total = $total;
                //$contribution->interes = 0;
                $contribution->type='Planilla';
                $contribution->save();                        
            }
        }
        return json_encode($contribution);
    }
}
