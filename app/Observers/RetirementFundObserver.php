<?php

namespace Muserpol\Observers;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunRecord;
use Auth;
use Log;
use Carbon\Carbon;
use Muserpol\Models\Workflow\WorkflowRecord;
use Muserpol\Models\Workflow\WorkflowState;
class RetirementFundObserver
{
    public function created(RetirementFund $rf)
    {
        $retfun = new RetFunRecord;
        $retfun->user_id = Auth::user()->id;
        $retfun->ret_fun_id = $rf->id;
        $retfun->message = 'El usuario '.Auth::user()->username. ' creo el Trámite '.$rf->code.' con la modalidad '.$rf->procedure_modality->name.' '.Carbon::now();
        $retfun->save();
    }
    private function defaultValuesWfRecord($wf_state_current_id = null , $record_type_id = null, $message = null, $old_wf_state_id = null, $old_user_id = null)
    {
        $default = [
            'user_id' => Auth::user()->id,
            'date' => Carbon::now(),
            'wf_state_id' => $wf_state_current_id,
            'record_type_id' => $record_type_id,
            'message' => $message,
            'old_wf_state_id' => $old_wf_state_id,
            'old_user_id' => $old_user_id,
        ];
        return $default;
    }
    public function updating(RetirementFund $rf)
    {
        $old = RetirementFund::find($rf->id);
        
        $message = 'El usuario '.Auth::user()->username.' modifico ';
        $changes = '';  
        if($rf->city_start_id != $old->city_start_id)
        {
            $changes = $changes . ' ciudad de recepción  de '.$old->city_start->name.' a '.$rf->city_start->name.', ';
        }
        
        if($rf->city_end_id != $old->city_end_id)
        {
            $changes = $changes . ' regional  de '.$old->city_end->name.' a '.$rf->city_end->name.', ';
        }

        if($rf->reception_date != $old->reception_date)
        {
            $changes = $changes . ' fecha de recepción '.$old->reception_date.' a '.$rf->reception_date.', ';
        }

        if($rf->ret_fun_state_id != $old->ret_fun_state_id)
        {
            $changes = $changes . ' estado del trámite '.$old->ret_fun_state->name.' a '.$rf->ret_fun_state->name.', ';
        }

        if($changes != '')
        {
            $message = $message . $changes;
            $retfun = new RetFunRecord;
            $retfun->user_id = Auth::user()->id;
            $retfun->ret_fun_id = $rf->id;
            $retfun->message = $message;
            $retfun->save();
        }

        $wf_state_sequence = WorkflowState::find($rf->wf_state_current_id)->sequence_number;
        $old_wf_state_sequence = WorkflowState::find($old->wf_state_current_id)->sequence_number;

        if ( $rf->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence > $old_wf_state_sequence ) {
            $rf->wf_records()->create($this->defaultValuesWfRecord($rf->wf_state_current_id, 3, "El usuario " . Auth::user ()->username . " Derivó el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $rf->wf_state->name . ".", $old->wf_state_current_id, $old->user_id));
        }
        if ( $rf->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence < $old_wf_state_sequence ) {
            $rf->wf_records()->create($this->defaultValuesWfRecord($rf->wf_state_current_id, 4 , "El usuario " . Auth::user()->username . " Devolvió el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $rf->wf_state->name . " con Nota: " . request()->message . ".", $old->wf_state_current_id, $old->user_id));
        }
        if ( $old->inbox_state == false && $rf->inbox_state == true &&  $rf->wf_state_current_id == $old->wf_state_current_id  ) {
            $rf->wf_records()->create($this->defaultValuesWfRecord($rf->wf_state_current_id, 1,'El usuario ' . Auth::user ()->username . ' Validó el trámite.'));
        }
        if ($old->inbox_state == true && $rf->inbox_state == false && $rf->wf_state_current_id == $old->wf_state_current_id  ) {
            $rf->wf_records()->create($this->defaultValuesWfRecord($rf->wf_state_current_id, 2, 'El usuario ' . Auth::user()->username . ' Canceló el trámite.'));
        }
    }
}