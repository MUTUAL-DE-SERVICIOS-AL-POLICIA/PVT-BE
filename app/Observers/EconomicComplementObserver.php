<?php

namespace Muserpol\Observers;

use Log;
use Auth;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EconomicComplementRecord;
use Muserpol\Models\Workflow\WorkflowState;
use Carbon\Carbon;

class EconomicComplementObserver
{
    public function created(EconomicComplement $eco_com)
    {
        $record = new EconomicComplementRecord();
        $record->user_id = Auth::user()->id;
        $record->economic_complement_id = $eco_com->id;
        $record->message = "El usuario " . Auth::user()->username  . " creó el trámite " . $eco_com->code . ".";
        $record->save();
    }
    private function defaultValuesWfRecord($wf_current_state_id = null, $record_type_id = null, $message = null)
    {
        $default = [
            'user_id' => Auth::user()->id,
            'date' => Carbon::now(),
            'wf_state_id' => $wf_current_state_id,
            'record_type_id' => $record_type_id,
            'message' => $message,
        ];
        return $default;
    }
    public function updating(EconomicComplement $eco_com)
    {
        $old = EconomicComplement::find($eco_com->id);

        $message = 'El usuario ' . Auth::user()->username . ' modifico ';
        $temp = $message;
        if ($eco_com->city_start_id != $old->city_start_id) {
            $message = $message . ' ciudad de recepción  de ' . $old->city_start->name . ' a ' . $eco_com->city_start->name . ', ';
        }

        if ($eco_com->city_end_id != $old->city_end_id) {
            $message = $message . ' ciudad de recepción  de ' . $old->city_end->name . ' a ' . $eco_com->city_end->name . ', ';
        }

        if ($eco_com->reception_date != $old->reception_date) {
            $message = $message . ' fecha de recepción ' . $old->reception_date . ' a ' . $eco_com->reception_date . ', ';
        }
        if($temp !=  $message){
            $message = $message . ' ';
            $record = new EconomicComplementRecord();
            $record->user_id = Auth::user()->id;
            $record->economic_complement_id = $eco_com->id;
            $record->message = $message;
            $record->save();
        }

        $wf_state_sequence = WorkflowState::find($eco_com->wf_current_state_id)->sequence_number;
        $old_wf_state_sequence = WorkflowState::find($old->wf_current_state_id)->sequence_number;

        if ($eco_com->wf_current_state_id != $old->wf_current_state_id && $wf_state_sequence > $old_wf_state_sequence) {
            $eco_com->wf_records()->create($this->defaultValuesWfRecord($eco_com->wf_current_state_id, 3, "El usuario " . Auth::user()->username . " Derivó el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $eco_com->wf_state->name));
        }
        if ($eco_com->wf_current_state_id != $old->wf_current_state_id && $wf_state_sequence < $old_wf_state_sequence) {
            $eco_com->wf_records()->create($this->defaultValuesWfRecord($eco_com->wf_current_state_id, 4, "El usuario " . Auth::user()->username . " Devolvió el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $eco_com->wf_state->name . " con nota: " . request()->message . "."));
        }
        if ($old->inbox_state == false && $eco_com->inbox_state == true && $eco_com->wf_current_state_id == $old->wf_current_state_id) {
            $eco_com->wf_records()->create($this->defaultValuesWfRecord($eco_com->wf_current_state_id, 1, 'El usuario ' . Auth::user()->username . ' Validó el trámite.'));
        }
        if ($old->inbox_state == true && $eco_com->inbox_state == false && $eco_com->wf_current_state_id == $old->wf_current_state_id) {
            $eco_com->wf_records()->create($this->defaultValuesWfRecord($eco_com->wf_current_state_id, 2, 'El usuario ' . Auth::user()->username . ' Canceló el trámite.'));
        }
    }
}
