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
            $wf_record = new WorkflowRecord;
            $wf_record->user_id = Auth::user()->id;
            $wf_record->wf_state_id = $qa->wf_state_current_id;
            $wf_record->quota_aid_id = $qa->id;
            $wf_record->date = Carbon::now();
            $wf_record->record_type_id = 1;
            $wf_record->message = "El usuario " . Auth::user()->username . " derivo el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $qa->wf_state->name . ".";
            $wf_record->save();
        }
        if ($qa->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence < $old_wf_state_sequence) {
            $wf_record = new WorkflowRecord;
            $wf_record->user_id = Auth::user()->id;
            $wf_record->wf_state_id = $qa->wf_state_current_id;
            $wf_record->quota_aid_id = $qa->id;
            $wf_record->date = Carbon::now();
            $wf_record->record_type_id = 1;
            $wf_record->message = "El usuario " . Auth::user()->username . " devolvio el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $qa->wf_state->name . ".";
            $wf_record->save();
        }
        if ($old->inbox_state == false && $qa->inbox_state == true && $qa->wf_state_current_id == $old->wf_state_current_id) {
            $wf_record = new WorkflowRecord;
            $wf_record->user_id = Auth::user()->id;
            $wf_record->wf_state_id = $qa->wf_state_current_id;
            $wf_record->quota_aid_id = $qa->id;
            $wf_record->date = Carbon::now();
            $wf_record->record_type_id = 1;
            $wf_record->message = 'El usuario ' . Auth::user()->username . ' Valido el trámite.';
            $wf_record->save();
        }
        if ($old->inbox_state == true && $qa->inbox_state == false && $qa->wf_state_current_id == $old->wf_state_current_id) {
            $wf_record = new WorkflowRecord;
            $wf_record->user_id = Auth::user()->id;
            $wf_record->wf_state_id = $qa->wf_state_current_id;
            $wf_record->quota_aid_id = $qa->id;
            $wf_record->date = Carbon::now();
            $wf_record->record_type_id = 1;
            $wf_record->message = 'El usuario ' . Auth::user()->username . ' cancelo el trámite.';
            $wf_record->save();
        }
    }
}