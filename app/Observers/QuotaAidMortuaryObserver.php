<?php

namespace Muserpol\Observers;

use Auth;
use Log;
use Carbon\Carbon;
use Muserpol\Models\Workflow\WorkflowRecord;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\QuotaAidMortuary\QuotaAidRecord;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;

class QuotaAidMortuaryObserver
{
    public function created(QuotaAidMortuary $qa)
    {
        $quota_aid = new QuotaAidRecord() ;
        $quota_aid->user_id = Auth::user()->id;
        $quota_aid->quota_aid_id = $qa->id;
        $quota_aid->message = 'El usuario ' . Auth::user()->username . ' creo el Trámite ' . $qa->code . ' con la modalidad ' . $qa->procedure_modality->name . ' ' . Carbon::now();
        $quota_aid->save();
    }
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
    public function updating(QuotaAidMortuary $qa)
    {
        $old = QuotaAidMortuary::find($qa->id);

        $message = 'El usuario ' . Auth::user()->username . ' modifico ';
        if ($qa->city_start_id != $old->city_start_id) {
            $message = $message . ' ciudad de recepción  de ' . $old->city_start->name . ' a ' . $qa->city_start->name . ', ';

        }

        if ($qa->city_end_id != $old->city_end_id) {
            $message = $message . ' ciudad de recepción  de ' . $old->city_end->name . ' a ' . $qa->city_end->name . ', ';

        }

        if ($qa->reception_date != $old->reception_date) {
            $message = $message . ' fecha de recepción ' . $old->reception_date . ' a ' . $qa->reception_date . ', ';

        }

        $message = $message . ' ';

        $quota_aid = new QuotaAidRecord();
        $quota_aid->user_id = Auth::user()->id;
        $quota_aid->quota_aid_id = $qa->id;
        $quota_aid->message = $message;
        $quota_aid->save();

        $wf_state_sequence = WorkflowState::find($qa->wf_state_current_id)->sequence_number;
        $old_wf_state_sequence = WorkflowState::find($old->wf_state_current_id)->sequence_number;

        if ($qa->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence > $old_wf_state_sequence) {
            $qa->wf_records()->create($this->defaultValuesWfRecord($qa->wf_state_current_id, 3, "El usuario " . Auth::user()->username . " Derivó el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $qa->wf_state->name));

        }
        if ($qa->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence < $old_wf_state_sequence) {
            $qa->wf_records()->create($this->defaultValuesWfRecord($qa->wf_state_current_id,4,"El usuario " . Auth::user()->username . " Devolvió el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $qa->wf_state->name ." con nota: " . request()->message . "."));
        }
        if ($old->inbox_state == false && $qa->inbox_state == true && $qa->wf_state_current_id == $old->wf_state_current_id) {
            $qa->wf_records()->create($this->defaultValuesWfRecord($qa->wf_state_current_id,1,'El usuario ' . Auth::user()->username . ' Validó el trámite.'));
        }
        if ($old->inbox_state == true && $qa->inbox_state == false && $qa->wf_state_current_id == $old->wf_state_current_id) {
            $qa->wf_records()->create($this->defaultValuesWfRecord($qa->wf_state_current_id, 2, 'El usuario ' . Auth::user()->username . ' Canceló el trámite.'));
        }
    }
}