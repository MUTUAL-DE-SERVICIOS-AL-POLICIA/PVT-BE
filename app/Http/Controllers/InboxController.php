<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Helpers\Util;
use Muserpol\Workflow;
use Muserpol\Models\RetirementFund\RetirementFund;
use Illuminate\Validation\ValidationException;
use Log;
use DB;
use Auth;
use Muserpol\Models\Role;
use Muserpol\Models\Workflow\WorkflowState;
use Exception;
use Muserpol\Models\Workflow\WorkflowRecord;
use Carbon\Carbon;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Models\RetirementFund\RetFunCorrelative;

class InboxController extends Controller
{
    public function received()
    {
        return view('inbox.received');
    }
    public function edited()
    {
        return view('inbox.edited');
    }
    public function sendForward(Request $request)
    {
        try {
            $this->validate($request, [
                'docs' => 'required|min:1',
                'wfSequenceNext' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $doc_ids= array_map(function($doc){
            return $doc['id'];
        },$request->docs);
        $wf_state_next_id = $request->wfSequenceNext;

        $rol_id = Util::getRol()->id;
        $module = Role::find($rol_id)->module;
        switch ($module->id) {
            case 1:
                # code...
                break;
            case 2:
            /* TODO
            no crea historial del workflow (tema de observers)
            utilizar modelos
             */
                DB::table('economic_complements')
                    ->whereIn('id', $doc_ids)
                    ->update(['wf_current_state_id' => $wf_state_next_id, 'state' => 'Received']);
                break;
            case 3:
                // DB::table('retirement_funds')
                //     ->whereIn('id', $doc_ids)
                //     ->update([
                //         'wf_state_current_id' => $wf_state_next_id,
                //         'inbox_state' => false
                //     ]);
                $retirement_funds = RetirementFund::whereIn('id', $doc_ids)->get();
                foreach ($retirement_funds as $ret_fun) {
                    $ret_fun->wf_state_current_id = $wf_state_next_id;
                    $ret_fun->inbox_state = false;
                    $ret_fun->save();
                }
                break;
            case 4:
                $quota_aids = QuotaAidMortuary::whereIn('id', $doc_ids)->get();
                foreach ($quota_aids as $ret_fun) {
                    $ret_fun->wf_state_current_id = $wf_state_next_id;
                    $ret_fun->inbox_state = false;
                    $ret_fun->save();
                }
                break;
            case 11:
                $contribution_processes = ContributionProcess::whereIn('id', $doc_ids)->get();
                foreach ($contribution_processes as $ret_fun) {
                    $ret_fun->wf_state_current_id = $wf_state_next_id;
                    $ret_fun->inbox_state = false;
                    $ret_fun->save();
                }
                break;
            default:
                # code...
                break;
        }
        return response()->json([
            'status' => 'success',
            'msg' => 'Okay',
        ], 201);
    }
    public function sendBackward(Request $request)
    {
        try {
            $this->validate($request, [
                'docs' => 'required|min:1',
                'wfSequenceBack' => 'required',
                'message' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $doc_ids= array_map(function($doc){
            return $doc['id'];
        },$request->docs);
        $wf_state_back_id = $request->wfSequenceBack;

        $rol_id = Util::getRol()->id;
        $module = Role::find($rol_id)->module;
        switch ($module->id) {
            case 1:
                # code...
                break;
            case 2:
                /* TODO
                no crea historial del workflow (tema de observers)
                utilizar modelos
                */
                DB::table('economic_complements')
                ->whereIn('id', $doc_ids)
                ->update(['wf_current_state_id' => $wf_state_back_id, 'state'=>'Received']);
                break;
            case 3:
                $retirement_funds = RetirementFund::whereIn('id', $doc_ids)->get();
                foreach ($retirement_funds as $ret_fun) {
                    $ret_fun->wf_state_current_id = $wf_state_back_id;
                    $ret_fun->inbox_state = false;
                    $ret_fun->save();
                }
                break;
            case 4:
                $quota_aids = QuotaAidMortuary::whereIn('id', $doc_ids)->get();
                foreach ($quota_aids as $quota_aid) {
                    $quota_aid->wf_state_current_id = $wf_state_back_id;
                    $quota_aid->inbox_state = false;
                    $quota_aid->save();
                }
                break;
            case 11:
                $contribution_processes = ContributionProcess::whereIn('id', $doc_ids)->get();
                foreach ($contribution_processes as $doc) {
                    $doc->wf_state_current_id = $wf_state_back_id;
                    $doc->inbox_state = false;
                    $doc->save();
                }
                break;
            default:
                # code...
                break;
        }
        return response()->json([
            'status' => 'success',
            'msg' => 'Okay',
        ], 201);
    }
    public function validateDoc(Request $request, $doc_id)
    {
        $rol_id = Util::getRol()->id;
        $module = Role::find($rol_id)->module;
        try {
            switch ($module->id) {
                case 1:
                    break;
                case 2:
                    break;
                case 3:
                    $ret_fun = RetirementFund::find($doc_id);
                    if ($ret_fun->inbox_state == true) {
                        throw new Exception('Trámite ya validado.');
                    }
                    $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
                    if ($wf_current_state->id != $ret_fun->wf_state_current_id) {
                        throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
                    }
                    $ret_fun->inbox_state = true;
                    $ret_fun->user_id = Auth::user()->id;

                    $correlative = Util::getNextAreaCode($ret_fun->id);
                    /* TODO
                    * adicionar fechas de revision calificacion etc.
                    */
                    $ret_fun->save();

                    return response()->json([
                        'doc' => $ret_fun,
                        'correlative' => $correlative,
                    ], 200);
                    break;
                case 4:
                    $quota_aid = QuotaAidMortuary::find($doc_id);
                    if ($quota_aid->inbox_state == true) {
                        throw new Exception('Trámite ya validado.');
                    }
                    $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
                    if ($wf_current_state->id != $quota_aid->wf_state_current_id) {
                        throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
                    }
                    $quota_aid->inbox_state = true;
                    $quota_aid->user_id = Auth::user()->id;

                    $correlative = Util::getNextAreaCodeQuotaAid($quota_aid->id);

                    /* TODO
                    * adicionar fechas de revision calificacion etc.
                    */
                    $quota_aid->save();
                    return response()->json([
                        'doc' => $quota_aid,
                        'correlative' => $correlative,
                    ], 200);

                    break;
                case 11:
                    $doc = ContributionProcess::find($doc_id);
                    if ($doc->inbox_state == true) {
                        throw new Exception('Trámite ya validado.');
                    }
                    $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
                    if ($wf_current_state->id != $doc->wf_state_current_id) {
                        throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
                    }
                    $doc->inbox_state = true;
                    $doc->user_id = Auth::user()->id;

                    // $correlative = Util::getNextAreaCodeQuotaAid($doc->id);
                    $correlative = $doc;

                    /* TODO
                     * adicionar fechas de revision calificacion etc.
                     */
                    $doc->save();
                    break;
            }
            return response()->json([
                'doc' => $doc,
                'correlative' => $correlative,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->getMessage(),
            ], 422);
        }
    }
    public function invalidateDoc(Request $request, $doc_id)
    {
        $rol_id = Util::getRol()->id;
        $module = Role::find($rol_id)->module;
        switch ($module->id) {
            case 1:
                break;
            case 2:
                break;
            case 3:
                try {
                    $ret_fun = RetirementFund::find($doc_id);
                    if ($ret_fun->inbox_state == false) {
                        throw new Exception('Trámite aun no validado.');
                    }
                    $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
                    if ($wf_current_state->id != $ret_fun->wf_state_current_id) {
                        throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
                    }
                    $ret_fun->inbox_state = false;

                    $correlative = RetFunCorrelative::where('retirement_fund_id',$ret_fun->id)->where('wf_state_id',$wf_current_state->id)->first();
                    if(!isset($correlative->id)) {
                         throw new Exception('El trátmite no tiene correlativo.');
                    }
                    $correlative->delete();
                    /* TODO
                     * adicionar fechas de revision calificacion etc.
                     */
                    $ret_fun->save();
                } catch (Exception $exception) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $exception->getMessage(),
                    ], 422);
                }
                return response()->json($ret_fun, 200);
            break;
            case 4:
                try {
                    $quota_aid = QuotaAidMortuary::find($doc_id);
                    if ($quota_aid->inbox_state == false) {
                        throw new Exception('Trámite aun no validado.');
                    }
                    $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
                    if ($wf_current_state->id != $quota_aid->wf_state_current_id) {
                        throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
                    }
                    $quota_aid->inbox_state = false;
                    /* TODO
                     * adicionar fechas de revision calificacion etc.
                     */
                    $quota_aid->save();
                } catch (Exception $exception) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $exception->getMessage(),
                    ], 422);
                }
                return response()->json($quota_aid, 200);
            break;
            case 11:
                try {
                    $doc = ContributionProcess::find($doc_id);
                    if ($doc->inbox_state == false) {
                        throw new Exception('Trámite aun no validado.');
                    }
                    $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
                    if ($wf_current_state->id != $doc->wf_state_current_id) {
                        throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
                    }
                    $doc->inbox_state = false;
                    /* TODO
                     * adicionar fechas de revision calificacion etc.
                     */
                    $doc->save();
                } catch (Exception $exception) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $exception->getMessage(),
                    ], 422);
                }
                return response()->json($doc, 200);
            break;
        }
    }
}
