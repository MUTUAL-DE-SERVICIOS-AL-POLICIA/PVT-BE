<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\RetirementFund\RetirementFund;
use Auth;
use Muserpol\Models\ObservationType;
use Carbon\Carbon;
use Muserpol\Helpers\Util;

class RetirementFundObservationController extends Controller
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
        $ret_fun = RetirementFund::find($request->retirement_fund_id);
        $observation = ObservationType::find($request->observation_type_id);
        $ret_fun->ret_fun_observations()->save($observation, [
            'user_id' => Auth::user()->id,
            'date' => Carbon::now(),
            'message' => $request->message,
            'enabled' => $request->enabled
        ]);
        $ret_fun->procedure_records()->create([
            'user_id' => Auth::user()->id,
            'record_type_id' => 9,
            'wf_state_id' => Util::getRol()->wf_states->first()->id ?? $ret_fun->wf_current_state_id,
            'date' => Carbon::now(),
            'message' => "El usuario " . Auth::user()->username  . " creó la observación " . $observation->name
        ]);
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public function editObservation(Request $request){
        $ret_fun = RetirementFund::find($request->retirement_fund_id);
        $observation = ObservationType::find($request->observation_type_id);
        if($request->enable == 'is_enable')
            $enabled = true;
        if($request->enable == 'no_enable')
            $enabled = false;

        $old_observation = $ret_fun->ret_fun_observations()->find($observation->id);
        $ret_fun->ret_fun_observations()->updateExistingPivot($observation->id, [
            'message' => $request->message,
            'enabled' =>$enabled
        ]);
        $message = "El usuario " . Auth::user()->username  . " modifico la observación ". $observation->name.": ";
            if($old_observation->pivot->message != $request->message)
            {
                $message = $message . ' Mensaje de - '.$old_observation->pivot->message.' - a - '.$request->message.', ';
            }
            if($old_observation->pivot->enabled != $enabled)
            {
                $message = $message . ' de '.Util::getEnabledLabel($old_observation->pivot->enabled).' a '.Util::getEnabledLabel($enabled).', ';
            }
            $message = $message. ".";
            $ret_fun->procedure_records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => 9,
                'wf_state_id' => Util::getRol()->wf_states->first()->id ?? $ret_fun->wf_current_state_id,
                'date' => Carbon::now(),
                'message' => $message
            ]);
        return back()->withInput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ret_fun = RetirementFund::find($request->retirement_fund_id);
        $observation = ObservationType::find($request->id_observation);
        if($ret_fun->ret_fun_observations()->where('id',$observation->id)->count()>0){
            $ret_fun->ret_fun_observations()->updateExistingPivot($observation->id, [
                'deleted_at' => Carbon::now(),
            ]);
            $ret_fun->procedure_records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => 9,
                'wf_state_id' => Util::getRol()->wf_states->first()->id ?? $ret_fun->wf_current_state_id,
                'date' => Carbon::now(),
                'message' => "El usuario " . Auth::user()->username  . " eliminó la observación " . $observation->name
            ]);
        }
        return back()->withInput();
    }
}
