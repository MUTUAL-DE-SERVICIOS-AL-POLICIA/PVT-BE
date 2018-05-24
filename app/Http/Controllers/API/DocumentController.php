<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Models\RetirementFund\RetirementFund;
use Yajra\DataTables\DataTables;
use Muserpol\Helpers\Util;
use DB;
class DocumentController extends Controller
{
    public function index(Request $request, $rol_id)
    {
        // return DB::table('wf_states')->where('role_id', '=',10)->get();

        $documents = RetirementFund::select(
            DB::raw(
            "
                retirement_funds.id as id,
                affiliates.identity_card as ci,
                regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g') as name,
                retirement_funds.code as code,
                ret_fun_cities.second_shortened as city
            "

            )
        )
        ->leftJoin('affiliates', 'retirement_funds.affiliate_id', '=', 'affiliates.id')
        ->leftJoin('cities as ret_fun_cities', 'retirement_funds.city_end_id', '=', 'ret_fun_cities.id')
        ->leftJoin('wf_states', 'retirement_funds.wf_state_current_id', '=', 'wf_states.id')
        ->where('wf_states.role_id', '=', $rol_id)
        ->get();
        return DataTables::of($documents)
            ->editColumn('name', function ($document)
            {
                return  '<a href = "'.url('ret_fun',     [$document->id]).'">'.$document->name.'</a>';
            })
            ->rawColumns(['ci','name','code'])
            ->make(true);
    }
}
