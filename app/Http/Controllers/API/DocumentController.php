<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Models\RetirementFund\RetirementFund;
use Yajra\DataTables\DataTables;
use Muserpol\Helpers\Util;
use DB;
use Muserpol\Models\Role;
use Muserpol\Models\Workflow\Workflow;
use Auth;
use Log;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\Workflow\WorkflowSequence;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
class DocumentController extends Controller
{
    public function received(Request $request, $rol_id, $user_id)
    {
        $module = Role::find($rol_id)->module;
        // return DB::table('wf_states')->where('role_id', '=',10)->get();
        $headers = Util::getHeadersInboxRetFunQuotaAid();
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
                $documents_edited_total = DB::table('economic_complements')
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
                        concat('/economic_complement/', economic_complements.id) as path,
                        false as status
                        "
                    )
                )
                    ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as eco_com_cities', 'economic_complements.city_id', '=', 'eco_com_cities.id')
                    ->leftJoin('wf_states', 'economic_complements.wf_current_state_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('economic_complements.state', '=', 'Edited')
                    ->where('economic_complements.user_id', '=', $user_id)
                    ->get()->count();
                break;
            case 3:
                # ret fun
                $documents = RetirementFund::with('tags')->select(
                    DB::raw(
                        "
                        retirement_funds.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        retirement_funds.code as code,
                        ret_fun_cities.second_shortened as city,
                        retirement_funds.reception_date as reception_date,
                        retirement_funds.workflow_id as workflow_id,
                        procedure_modalities.name as modality,
                        concat('/ret_fun/', retirement_funds.id) as path
                        "
                        )
                    )
                    ->leftJoin('affiliates', 'retirement_funds.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as ret_fun_cities', 'retirement_funds.city_start_id', '=', 'ret_fun_cities.id')
                    ->leftJoin('wf_states', 'retirement_funds.wf_state_current_id', '=', 'wf_states.id')
                    ->leftJoin('procedure_modalities', 'retirement_funds.procedure_modality_id', '=', 'procedure_modalities.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('retirement_funds.inbox_state', '=', false)
                    ->where('retirement_funds.code', 'not like', '%A%')
                    ->orderBy(DB::raw("regexp_replace(split_part(code, '/',2),'\D','','g')::integer"))
                    ->orderBy(DB::raw("split_part(code, '/',1)::integer"))
                    ->get();
                $documents_edited_total = RetirementFund::select('retirement_funds.id as id')
                    ->leftJoin('affiliates', 'retirement_funds.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as ret_fun_cities', 'retirement_funds.city_start_id', '=', 'ret_fun_cities.id')
                    ->leftJoin('wf_states', 'retirement_funds.wf_state_current_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('retirement_funds.code', 'not like', '%A%')
                    ->where('retirement_funds.inbox_state', '=', true)
                    ->where('retirement_funds.user_id', '=', $user_id)
                    ->get()->count();
                break;
            case 4:
                # quota aid
                $documents = QuotaAidMortuary::with('tags')->select(
                    DB::raw(
                        "
                        quota_aid_mortuaries.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        quota_aid_mortuaries.code as code,
                        quota_aid_cities.second_shortened as city,
                        quota_aid_mortuaries.reception_date as reception_date,
                        quota_aid_mortuaries.workflow_id as workflow_id,
                        procedure_modalities.name as modality,
                        concat('/quota_aid/', quota_aid_mortuaries.id) as path
                        "
                    )
                )
                    ->leftJoin('affiliates', 'quota_aid_mortuaries.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as quota_aid_cities', 'quota_aid_mortuaries.city_start_id', '=', 'quota_aid_cities.id')
                    ->leftJoin('wf_states', 'quota_aid_mortuaries.wf_state_current_id', '=', 'wf_states.id')
                    ->leftJoin('procedure_modalities', 'quota_aid_mortuaries.procedure_modality_id', '=', 'procedure_modalities.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('quota_aid_mortuaries.inbox_state', '=', false)
                    ->where('quota_aid_mortuaries.code', 'not like', '%A%')
                    ->orderBy(DB::raw("regexp_replace(split_part(code, '/',2),'\D','','g')::integer"))
                    ->orderBy(DB::raw("split_part(code, '/',1)::integer"))
                    ->get();
                $documents_edited_total = QuotaAidMortuary::select('quota_aid_mortuaries.id as id')
                    ->leftJoin('affiliates', 'quota_aid_mortuaries.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as quota_aid_cities', 'quota_aid_mortuaries.city_start_id', '=', 'quota_aid_cities.id')
                    ->leftJoin('wf_states', 'quota_aid_mortuaries.wf_state_current_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('quota_aid_mortuaries.code', 'not like', '%A%')
                    ->where('quota_aid_mortuaries.inbox_state', '=', true)
                    ->where('quota_aid_mortuaries.user_id', '=', $user_id)
                    ->get()->count();
                break;
            case 11:
                # contributions
                $headers = Util::getHeadersInboxTreasury();
                $documents = Voucher::select(
                    DB::raw(
                        "
                        quota_aid_mortuaries.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        quota_aid_mortuaries.code as code,
                        quota_aid_cities.second_shortened as city,
                        quota_aid_mortuaries.reception_date as reception_date,
                        quota_aid_mortuaries.workflow_id as workflow_id,
                        procedure_modalities.name as modality,
                        concat('/quota_aid/', quota_aid_mortuaries.id) as path
                        "
                    )
                )
                    ->leftJoin('affiliates', 'quota_aid_mortuaries.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as quota_aid_cities', 'quota_aid_mortuaries.city_start_id', '=', 'quota_aid_cities.id')
                    ->leftJoin('wf_states', 'quota_aid_mortuaries.wf_state_current_id', '=', 'wf_states.id')
                    ->leftJoin('procedure_modalities', 'quota_aid_mortuaries.procedure_modality_id', '=', 'procedure_modalities.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('quota_aid_mortuaries.inbox_state', '=', false)
                    ->where('quota_aid_mortuaries.code', 'not like', '%A%')
                    ->orderBy(DB::raw("regexp_replace(split_part(code, '/',2),'\D','','g')::integer"))
                    ->orderBy(DB::raw("split_part(code, '/',1)::integer"))
                    ->get();
                $documents_edited_total = QuotaAidMortuary::select('quota_aid_mortuaries.id as id')
                    ->leftJoin('affiliates', 'quota_aid_mortuaries.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as quota_aid_cities', 'quota_aid_mortuaries.city_start_id', '=', 'quota_aid_cities.id')
                    ->leftJoin('wf_states', 'quota_aid_mortuaries.wf_state_current_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('quota_aid_mortuaries.code', 'not like', '%A%')
                    ->where('quota_aid_mortuaries.inbox_state', '=', true)
                    ->where('quota_aid_mortuaries.user_id', '=', $user_id)
                    ->get()->count();
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
            'documents_received_total' => $documents->count() ?? 0,
            'documents_edited_total' => $documents_edited_total ?? 0,
            'documents' => $documents,
            'workflows' => $workflows,
            'headers' => $headers
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
                        concat('/economic_complement/', economic_complements.id) as path,
                        false as status
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
                $documents_received_total = DB::table('economic_complements')
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
                    ->get()->count();
                break;
            case 3:
                # ret fun
                $documents = RetirementFund::with('tags')->select(
                    DB::raw(
                        "
                        retirement_funds.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        retirement_funds.code as code,
                        ret_fun_cities.second_shortened as city,
                        procedure_modalities.name as modality,
                        retirement_funds.reception_date as reception_date,
                        retirement_funds.workflow_id as workflow_id,
                        concat('/ret_fun/', retirement_funds.id) as path,
                        false as status
                        "
                        )
                    )
                    ->leftJoin('affiliates', 'retirement_funds.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as ret_fun_cities', 'retirement_funds.city_start_id', '=', 'ret_fun_cities.id')
                    ->leftJoin('wf_states', 'retirement_funds.wf_state_current_id', '=', 'wf_states.id')
                    ->leftJoin('procedure_modalities', 'retirement_funds.procedure_modality_id', '=', 'procedure_modalities.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('retirement_funds.inbox_state', '=', true)
                    ->where('retirement_funds.user_id', '=', $user_id)
                    ->where('retirement_funds.code', 'not like', '%A%')
                    ->orderBy(DB::raw("regexp_replace(split_part(code, '/',2),'\D','','g')::integer"))
                    ->orderBy(DB::raw("split_part(code, '/',1)::integer"))
                    ->get();
                $documents_received_total = RetirementFund::select('retirement_funds.id as id')
                    ->leftJoin('affiliates', 'retirement_funds.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as ret_fun_cities', 'retirement_funds.city_start_id', '=', 'ret_fun_cities.id')
                    ->leftJoin('wf_states', 'retirement_funds.wf_state_current_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('retirement_funds.inbox_state', '=', false)
                    ->where('retirement_funds.code', 'not like', '%A%')
                    ->get()->count();
                break;
            case 4:
                # quota aid
                $documents = QuotaAidMortuary::with('tags')->select(
                    DB::raw(
                        "
                        quota_aid_mortuaries.id as id,
                        affiliates.identity_card as ci,
                        trim(regexp_replace(concat_ws(' ', affiliates.first_name,affiliates.second_name,affiliates.last_name,affiliates.mothers_last_name, affiliates.surname_husband), '\s+', ' ', 'g')) as name,
                        quota_aid_mortuaries.code as code,
                        quota_aid_cities.second_shortened as city,
                        procedure_modalities.name as modality,
                        quota_aid_mortuaries.reception_date as reception_date,
                        quota_aid_mortuaries.workflow_id as workflow_id,
                        concat('/quota_aid/', quota_aid_mortuaries.id) as path,
                        false as status
                        "
                    )
                )
                    ->leftJoin('affiliates', 'quota_aid_mortuaries.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as quota_aid_cities', 'quota_aid_mortuaries.city_start_id', '=', 'quota_aid_cities.id')
                    ->leftJoin('wf_states', 'quota_aid_mortuaries.wf_state_current_id', '=', 'wf_states.id')
                    ->leftJoin('procedure_modalities', 'quota_aid_mortuaries.procedure_modality_id', '=', 'procedure_modalities.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('quota_aid_mortuaries.inbox_state', '=', true)
                    ->where('quota_aid_mortuaries.user_id', '=', $user_id)
                    ->where('quota_aid_mortuaries.code', 'not like', '%A%')
                    ->orderBy(DB::raw("regexp_replace(split_part(code, '/',2),'\D','','g')::integer"))
                    ->orderBy(DB::raw("split_part(code, '/',1)::integer"))
                    ->get();
                $documents_received_total = QuotaAidMortuary::select('quota_aid_mortuaries.id as id')
                    ->leftJoin('affiliates', 'quota_aid_mortuaries.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('cities as quota_aid_cities', 'quota_aid_mortuaries.city_start_id', '=', 'quota_aid_cities.id')
                    ->leftJoin('wf_states', 'quota_aid_mortuaries.wf_state_current_id', '=', 'wf_states.id')
                    ->where('wf_states.role_id', '=', $rol_id)
                    ->where('quota_aid_mortuaries.inbox_state', '=', false)
                    ->where('quota_aid_mortuaries.code', 'not like', '%A%')
                    ->get()->count();
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
        $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id','=', $module->id)->first();
        if ($wf_current_state) {
            $wf_sequences_next = WorkflowSequence::leftJoin('wf_states', 'wf_sequences.wf_state_next_id', '=', 'wf_states.id')
                ->whereIn("workflow_id", $temp)
                ->where("wf_state_current_id", $wf_current_state->id)
                ->where('action', 'Aprobar')
                ->select('wf_states.id as wf_state_id', 'workflow_id as workflow_id', 'wf_states.name as wf_state_name')
                ->get();
            /* TODO (improve)*/
            $wf_sequences_back = DB::table("wf_states")
            // ->whereIn("workflow_id", $temp)
            ->where("wf_states.module_id", "=", $module->id)
            // ->where("wf_state_current_id", $wf_current_state->id)
            ->where('wf_states.sequence_number', '<', $wf_current_state->sequence_number)
            ->select('wf_states.id as wf_state_id',
            //      'workflow_id as workflow_id',
            'wf_states.name as wf_state_name')
            ->get();
        }else{
            $wf_sequences_next = null;
            $wf_sequences_back = null;
        }
        $workflows = Workflow::whereIn('id',$temp)->get();
        $headers = Util::getHeadersInboxRetFunQuotaAid();
        $data = [
            'documents_received_total' => $documents_received_total ?? 0,
            'documents_edited_total' => $documents->count() ?? 0,
            'documents' => $documents,
            'workflows' => $workflows,
            'wf_sequences_next' => $wf_sequences_next,
            'wf_current_state' => $wf_current_state,
            'wf_sequences_back' => $wf_sequences_back,
            'headers' => $headers,
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
