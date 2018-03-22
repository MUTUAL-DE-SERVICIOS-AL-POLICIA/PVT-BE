<?php
namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\AidContribution;
use Muserpol\Models\Contribution\AidCommitment;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Helpers\Util;
use Muserpol\Models\City;
use Auth;
use Validator;

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
    public function getAllCommitmentAid ($id)
    {
         $commitment = AidContribution::where('affiliate_id', $id)
                        ->orderBy('month_year', 'desc')
                        ->first();
        $array_date = explode('-',$commitment->month_year);
        $gestion = $array_date[1];
        $month = $array_date[0];
        $type = $commitment->type;
        $quotable = $commitment->quotable;
        $rent = $commitment->rent;
        $dignity_rent = $commitment->dignity_rent;
        $total = $commitment->total;
        $data = [
            'year' =>  $gestion,
            'month' => $month,
            'type' => $type,
            'quotable' => $quotable,
            'rent' => $rent,
            'dignity_rent' => $dignity_rent,
            'total' => $total,
    ];
    return ($data);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\AidContribution  $aid_contribution
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
        //return $this->getContributionDebt($affiliate->id,3);
        //return 0;
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
            'new_contributions' => $this->getContributionDebt($affiliate->id,3),
            //'commitment'    =>  $commitment,
            'today_date'         =>  date('Y-m-d'),
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
                $input_data['dignity_rent'][$key]= strip_tags($request->dignity_rent[$key]);
                $input_data['total'][$key]= strip_tags($request->total[$key]);
                $array_rules = [                       
                    'rent.'.$key =>  'required|numeric',
                    'dignity_rent.'.$key =>  'required|numeric|min:1',
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
                $contribution->dignity_rent = strip_tags($request->dignity_rent[$key]) ?? $contribution->dignity_rent;
                $contribution->save();
            } else {                
                $contribution = new AidContribution();
                $contribution->user_id = Auth::user()->id;
                $contribution->affiliate_id = $request->affiliate_id;                                                
                $contribution->rent = strip_tags($request->rent[$key]) ?? 0;
                $contribution->month_year = $key;
                $contribution->dignity_rent = strip_tags($request->dignity_rent[$key]) ?? 0;                                
                $contribution->total = strip_tags($request->total[$key]) ?? 0;
                $contribution->quotable = $contribution->rent-$contribution->dinity_rent;
                $contribution->type = 'PLANILLA';
                $contribution->save();
            }
        }
        return $contribution;        
        }
    }
    
    private function getContributionDebt($affiliate_id,$number){        
        $contributions = [];
        $month = date('m');
        $year = date('Y');        
        while($number--){
            $month--;            
            if($month == 0){
                $month == 12;
                $year--;
            }
            $year_month = $year.'-'.$month<10?'0'.$month:$month.'-01';
            $contribution = AidContribution::where('affiliate_id',$affiliate_id)->where('month_year',$year_month)->get();            
            if(!isset($contribution->id))
                array_push (
                    $contributions,
                    array('year' => $year, 'month' => $month<10?'0'.$month:$month, 'monthyear' => $year_month, 'sueldo' => 0, 'fr' => 0, 'cm' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $affiliate_id)
                );                       
        }
        return $contributions;
    }
    
}