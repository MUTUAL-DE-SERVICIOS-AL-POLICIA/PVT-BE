<?php

namespace Muserpol\Http\Controllers;


use Illuminate\Http\Request;

use Muserpol\Models\Affiliate;
use Muserpol\Models\Contribution\Contribution;
use DateTime;
class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $dateentry=$this->getStringDate($affiliate->date_entry);
        //return $fondoret.'    '.$quotaaid.'    '.$total.'    '.$affiliate->date_entry;
        $data= array( 
            'fondoret' => $fondoret,
            'quotaaid' => $quotaaid,
            'total' => $total,
            'dateentry' => $dateentry
        );
        return view('contribution.aditional_info')->with($data);
    }

    private function getStringDate($string = "1800/01/01"){        
        setlocale(LC_TIME, 'es_ES.utf8');        
        $date = DateTime::createFromFormat("Y-m-d", $string);
        if($date)
            return strftime("%d de %B de %Y",$date->getTimestamp());
        else 
            return "sin fecha";
        
    }
}
