<?php
namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\AidContribution;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Carbon\Carbon;
use Yajra\Datatables\DataTables;
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
     * @param  \Muserpol\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function show(Affiliate $affiliate)
    {

    }
    public function aidContributions($affiliate_id)
    {

        $affiliate = Affiliate::find($affiliate_id);
        $data = [
            'affiliate'=>$affiliate
        ];
        return view ('contribution.aid_contribution', $data);
    }
    public function getAllContributionsAid (DataTables $datatables, $affiliate_id)
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
        $dignity_rent = $aid_contributions->dignity_rent;
        $total = $aid_contributions->total;
        $data = [
            
            'affiliate' =>  $affiliate,
            'year' =>  $year,
            'month' => $month,
            'type' => $type,
            'quotable' => $quotable,
            'rent' => $rent,
            'dignity_rent' => $dignity_rent,
            'total' => $total,
        ];
        return view ('contribution.aid_contribution', $data);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function getMonthContributions($id)
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
                $contribution1 = array('year' => $month1->format('Y'), 'month' => $month1->format('m'), 'rent' => 0, 'Auxilio Morutorio' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
                $contribution2 = array('year' => $month2->format('Y'), 'month' => $month2->format('m'), 'rent' => 0, 'Auxilio Morutorio' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
                $contribution3 = array('year' => $month3->format('Y'), 'month' => $month3->format('m'), 'rent' => 0, 'Auxilio Morutorio' => 0, 'interes' => 0, 'subtotal' => 0, 'affiliate_id' => $id);
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
                        //'monthyear' => $montyear, 
                        'rent' => 0, 
                        'Auxilio Mortuorio' => 0,
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
    
}