<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Models\RetirementFund\RetirementFund;
use Yajra\DataTables\DataTables;
use Muserpol\Helpers\Util;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        
        $documents = RetirementFund::select(
            'retirement_funds.id',
            'affiliates.identity_card as ci',
            'affiliates.first_name as name',
            'retirement_funds.code as code',
            'ret_fun_cities.name as city'
        )
        ->leftJoin('affiliates', 'retirement_funds.affiliate_id', '=', 'affiliates.id')
        ->leftJoin('cities as ret_fun_cities', 'retirement_funds.city_end_id', '=', 'ret_fun_cities.id')
        ->take(100)
        ->get();
        return DataTables::of($documents)
            ->make(true);
    }
}
