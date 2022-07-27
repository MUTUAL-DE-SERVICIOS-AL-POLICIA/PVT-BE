<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Auth;
use Muserpol\Models\ObservationType;
use Carbon\Carbon;
use Muserpol\Helpers\Util;

class QuotaAidObservationController extends Controller
{
    public function store(Request $request)
    {
        $quota_aid = QuotaAidMortuary::find($request->quota_aid_id);
        $observation = ObservationType::find($request->observation_type_id);
        $quota_aid->quota_aid_observations()->save($observation, [
            'user_id' => Auth::user()->id,
            'date' => Carbon::now(),
            'message' => $request->message,
            'enabled' => $request->enabled
        ]);
        $quota_aid->procedure_records()->create([
            'user_id' => Auth::user()->id,
            'record_type_id' => 9,
            'wf_state_id' => Util::getRol()->wf_states->first()->id ?? $quota_aid->wf_current_state_id,
            'date' => Carbon::now(),
            'message' => "El usuario " . Auth::user()->username  . " eliminó la observación " . $observation->name
        ]);
        return back()->withInput();
    }
    public function editObservation(Request $request){
        $quota_aid = QuotaAidMortuary::find($request->quota_aid_id);
        $observation = ObservationType::find($request->observation_type_id);
        if($request->enable == 'is_enable')
            $enabled = true;
        if($request->enable == 'no_enable')
            $enabled = false;
        
        $old_observation = $quota_aid->quota_aid_observations()->find($observation->id);
        $quota_aid->quota_aid_observations()->updateExistingPivot($observation->id, [
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
            $quota_aid->procedure_records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => 9,
                'wf_state_id' => Util::getRol()->wf_states->first()->id ?? $quota_aid->wf_current_state_id,
                'date' => Carbon::now(),
                'message' => $message
            ]);
        return back()->withInput();
    }
    public function destroy(Request $request)
    {
        $quota_aid = QuotaAidMortuary::find($request->quota_aid_id);
        $observation = ObservationType::find($request->id_observation);
        if ($quota_aid->quota_aid_observations()->where('id', $observation->id)->count()>0) {
            $quota_aid->quota_aid_observations()->updateExistingPivot($observation->id, [
                'deleted_at' => Carbon::now(),
            ]);
            $quota_aid->procedure_records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => 9,
                'wf_state_id' => Util::getRol()->wf_states->first()->id ?? $quota_aid->wf_current_state_id,
                'date' => Carbon::now(),
                'message' => "El usuario " . Auth::user()->username  . " eliminó la observación " . $observation->name
            ]);
        }
        return back()->withInput();
    }
}
