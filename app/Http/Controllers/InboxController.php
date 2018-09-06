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
class InboxController extends Controller
{
    public function received()
    {
        $module_id = Util::getRol()->module->id;
        switch ($module_id) {
            case 2:
                # eco com
                break;
            case 3:
                # ret fun
                // return "hola";
                break;
            case 4:
                # cm
                break;
            case 5:
                # am
            default:
                # code...
                break;
        }
        return view('inbox.received');
    }
    public function edited()
    {
        $module_id = Util::getRol()->module->id;
        switch ($module_id) {
            case 2:
                # eco com
                break;
            case 3:
                # ret fun
                // return "hola";
                break;
            case 4:
                # cm
                break;
            case 5:
                # am
            default:
                # code...
                break;
        }
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
                    $wf_record = new WorkflowRecord() ;
                    $wf_record->user_id = Auth::user()->id;
                    $wf_record->wf_state_id = $wf_state_back_id;
                    $wf_record->ret_fun_id = $ret_fun->id;
                    $wf_record->date = Carbon::now();
                    $wf_record->record_type_id = 2;
                    $wf_record->message = "El usuario " . Auth::user()->username . " devolvio el trámite " . $ret_fun->code . " con nota: " . $request->message . ".";
                    $wf_record->save();
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
        switch ($module->id) {
            case 1:
            break;
            case 2:
            break;
            case 3:
                try {
                    $ret_fun = RetirementFund::find($doc_id);
                    if ($ret_fun->inbox_state == true) {
                        throw new Exception('Trámite ya validado.');
                    }
                    $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
                    if($wf_current_state->id != $ret_fun->wf_state_current_id){
                        throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
                    }
                    $ret_fun->inbox_state = true;
                    $ret_fun->user_id = Auth::user()->id;


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
        }
    }
}
