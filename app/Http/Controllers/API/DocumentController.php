<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Models\RetirementFund\RetirementFund;
use Yajra\DataTables\DataTables;
use Muserpol\Helpers\Util;
use DB;
use Muserpol\Models\Role;
use Muserpol\Workflow;
use Auth;
class DocumentController extends Controller
{
    public function received(Request $request, $rol_id)
    {
        $module = Role::find($rol_id)->module;
        // return DB::table('wf_states')->where('role_id', '=',10)->get();
        switch ($module->id) {
            case 1:
                # code...
                break;
            case 2:
                # eco com
                $documents = DB::table('economic_complements')
                    ->select(
                    DB::raw(
                            "
                        economic_complements.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        economic_complements.code as code,
                        eco_com_cities.second_shortened as city,
                        economic_complements.reception_date as reception_date,
                        economic_complements.workflow_id as workflow_id,
                        concat('/economic_complement/', economic_complements.id) as path
                        "
                    )
                )
                    ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as eco_com_cities', 'economic_complements.city_id', '=', 'eco_com_cities.id')
                    ->leftJoin('wf_states', 'economic_complements.wf_current_state_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('economic_complements.state', '=', 'Received')
                    ->get();
                $temp = Workflow::leftJoin('modules', 'workflows.module_id', '=', 'modules.id')
                    ->leftJoin('roles', 'modules.id', '=', 'roles.module_id')
                    ->select('workflows.id')
                    ->where('roles.id', '=', $rol_id)
                    ->pluck('id');
                break;
            case 3:
                # ret fun
                $documents = RetirementFund::select(
                    DB::raw(
                        "
                        retirement_funds.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        retirement_funds.code as code,
                        ret_fun_cities.second_shortened as city,
                        retirement_funds.reception_date as reception_date,
                        retirement_funds.workflow_id as workflow_id,
                        concat('/ret_fun/', retirement_funds.id) as path
                        "
                        )
                    )
                    ->leftJoin('affiliates', 'retirement_funds.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as ret_fun_cities', 'retirement_funds.city_end_id', '=', 'ret_fun_cities.id')
                    ->leftJoin('wf_states', 'retirement_funds.wf_state_current_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('retirement_funds.inbox_state', '=', false)
                    ->get();

                $temp = Workflow::leftJoin('modules', 'workflows.module_id', '=', 'modules.id')
                    ->leftJoin('roles', 'modules.id', '=', 'roles.module_id')
                    ->select('workflows.id')
                    ->where('roles.id', '=', $rol_id)
                    ->pluck('id');
                break;
            
            default:
                # code...
                break;
        }
        


        $workflows = Workflow::whereIn('id',$temp)->get();
        $data = [
            'documents' => $documents,
            'workflows' => $workflows
        ];
        return $data;
        // return DataTables::of($documents)
        //     ->editColumn('name', function ($document)
        //     {
        //         return  '<a href = "'.url('ret_fun',     [$document->id]).'">'.$document->name.'</a>';
        //     })
        //     ->rawColumns(['ci','name','code'])
        //     ->make(true);
    }

    public function edited(Request $request, $rol_id, $user_id)
    {
        
        $module = Role::find($rol_id)->module;
        switch ($module->id) {
            case 1:
                # code...
                break;
            case 2:
                # eco com
                $documents = DB::table('economic_complements')
                ->select(
                    DB::raw(
                            "
                        economic_complements.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        economic_complements.code as code,
                        eco_com_cities.second_shortened as city,
                        economic_complements.reception_date as reception_date,
                        economic_complements.workflow_id as workflow_id,
                        concat('/economic_complement/', economic_complements.id) as path
                        "
                    )
                )
                    ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as eco_com_cities', 'economic_complements.city_id', '=', 'eco_com_cities.id')
                    ->leftJoin('wf_states', 'economic_complements.wf_current_state_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('economic_complements.state', '=', 'Edited')
                    ->where('economic_complements.user_id', '=', $user_id)
                    ->get();
                dd($documents);
                break;
            case 3:
                # ret fun
                $documents = RetirementFund::select(
                    DB::raw(
                        "
                        retirement_funds.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        retirement_funds.code as code,
                        ret_fun_cities.second_shortened as city,
                        retirement_funds.reception_date as reception_date,
                        retirement_funds.workflow_id as workflow_id,
                        concat('/ret_fun/', retirement_funds.id) as path
                        "
                        )
                    )
                    ->leftJoin('affiliates', 'retirement_funds.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as ret_fun_cities', 'retirement_funds.city_end_id', '=', 'ret_fun_cities.id')
                    ->leftJoin('wf_states', 'retirement_funds.wf_state_current_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('retirement_funds.inbox_state', '=', true)
                    ->where('retirement_funds.user_id', '=', $user_id)
                    ->get();
                break;
            default:
                # code...
                break;
        }
        $temp = Workflow::leftJoin('modules', 'workflows.module_id', '=', 'modules.id')
            ->leftJoin('roles', 'modules.id', '=', 'roles.module_id')
            ->select('workflows.id')
            ->where('roles.id', '=', $rol_id)
            ->pluck('id');
        $workflows = Workflow::whereIn('id',$temp)->get();
        $data = [
            'documents' => $documents,
            'workflows' => $workflows
        ];
        return $data;
        // return DataTables::of($documents)
        //     ->editColumn('name', function ($document)
        //     {
        //         return  '<a href = "'.url('ret_fun',     [$document->id]).'">'.$document->name.'</a>';
        //     })
        //     ->rawColumns(['ci','name','code'])
        //     ->make(true);
    }
}
