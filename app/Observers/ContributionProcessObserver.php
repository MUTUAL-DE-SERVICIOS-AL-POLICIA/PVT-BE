<?php

namespace Muserpol\Observers;

use Auth;
use Log;
use Carbon\Carbon;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Models\Workflow\WorkflowState;

class ContributionProcessObserver
{
    private function defaultValuesWfRecord($wf_state_current_id = null, $record_type_id = null, $message = null)
    {
        $default = [
            'user_id' => Auth::user()->id,
            'date' => Carbon::now(),
            'wf_state_id' => $wf_state_current_id,
            'record_type_id' => $record_type_id,
            'message' => $message,
        ];
        return $default;
    }
    public function created(ContributionProcess $cp)
    {
        $cp->wf_records()->create($this->defaultValuesWfRecord($cp->wf_state_current_id, 1, 'El usuario ' . Auth::user()->username . ' creó el Trámite ' . $cp->code . '.'));
    }
    public function updating(ContributionProcess $cp)
    {
        $old = ContributionProcess::find($cp->id);

        // $message = 'El usuario ' . Auth::user()->username . ' modifico ';
        // if ($cp->city_start_id != $old->city_start_id) {
        //     $message = $message . ' ciudad de recepción  de ' . $old->city_start->name . ' a ' . $cp->city_start->name . ', ';

        // }

        // if ($cp->city_end_id != $old->city_end_id) {
        //     $message = $message . ' ciudad de recepción  de ' . $old->city_end->name . ' a ' . $cp->city_end->name . ', ';

        // }

        // if ($cp->reception_date != $old->reception_date) {
        //     $message = $message . ' fecha de recepción ' . $old->reception_date . ' a ' . $cp->reception_date . ', ';

        // }

        // $message = $message . ' ';

        // $retfun = new RetFunRecord;
        // $retfun->user_id = Auth::user()->id;
        // $retfun->ret_fun_id = $cp->id;
        // $retfun->message = $message;
        // $retfun->save();

        $wf_state_sequence = WorkflowState::find($cp->wf_state_current_id)->sequence_number;
        $old_wf_state_sequence = WorkflowState::find($old->wf_state_current_id)->sequence_number;

        if ($cp->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence > $old_wf_state_sequence) {
            $cp->wf_records()->create($this->defaultValuesWfRecord($cp->wf_state_current_id, 1, "El usuario " . Auth::user()->username . " Derivó el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $cp->wf_state->name . "."));
        }
        if ($cp->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence < $old_wf_state_sequence) {
            $cp->wf_records()->create($this->defaultValuesWfRecord($cp->wf_state_current_id, 1, "El usuario " . Auth::user()->username . " Devolvió el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $cp->wf_state->name . " con Nota: " . request()->message . "."));
        }
        if ($old->inbox_state == false && $cp->inbox_state == true && $cp->wf_state_current_id == $old->wf_state_current_id) {
            $cp->wf_records()->create($this->defaultValuesWfRecord($cp->wf_state_current_id, 1, 'El usuario ' . Auth::user()->username . ' Validó el trámite.'));
        }
        if ($old->inbox_state == true && $cp->inbox_state == false && $cp->wf_state_current_id == $old->wf_state_current_id) {
            $cp->wf_records()->create($this->defaultValuesWfRecord($cp->wf_state_current_id, 1, 'El usuario ' . Auth::user()->username . ' Canceló el trámite.'));
        }
    }
}