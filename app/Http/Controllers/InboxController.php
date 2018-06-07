<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Helpers\Util;
use Muserpol\Workflow;
use Muserpol\Models\RetirementFund\RetirementFund;
use Illuminate\Validation\ValidationException;
use Log;
use DB;
use Muserpol\Models\Role;
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
                DB::table('economic_complements')
                    ->whereIn('id', $doc_ids)
                    ->update(['wf_current_state_id' => $wf_state_next_id, 'state' => 'Received']);
                break;
            case 3:

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
        return ($request->all());
    }
    public function sendBackward(Request $request)
    {
        try {
            $this->validate($request, [
                'docs' => 'required|min:1',
                'wfSequenceBack' => 'required',
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
            default:
                # code...
                break;
        }
        return response()->json([
            'status' => 'success',
            'msg' => 'Okay',
        ], 201);
    }
}
