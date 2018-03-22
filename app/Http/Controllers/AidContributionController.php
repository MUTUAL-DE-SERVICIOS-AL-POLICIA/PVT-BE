<?php
namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\AidContribution;
use Muserpol\AidCommitment;
use Muserpol\Models\City;
use Illuminate\Http\Request;
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
    public function show()
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
    
}