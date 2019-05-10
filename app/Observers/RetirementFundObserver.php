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

        // Log::info('se creo el trámite con el id '.$retfun->id);
    }
    private function defaultValuesWfRecord($wf_state_current_id = null , $record_type_id = null, $message = null)
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
    public function updating(RetirementFund $rf)
    {
        $old = RetirementFund::find($rf->id);
        
        $message = 'El usuario '.Auth::user()->username.' modifico ';
        if($rf->city_start_id != $old->city_start_id)
        {
            $message = $message . ' ciudad de recepción  de '.$old->city_start->name.' a '.$rf->city_start->name.', ';

        }
        
        if($rf->city_end_id != $old->city_end_id)
        {
            $message = $message . ' ciudad de recepción  de '.$old->city_end->name.' a '.$rf->city_end->name.', ';
            
        }

        if($rf->reception_date != $old->reception_date)
        {
            $message = $message . ' fecha de recepción '.$old->reception_date.' a '.$rf->reception_date.', ';

        }

        $message = $message . ' ';

        $retfun = new RetFunRecord;
        $retfun->user_id = Auth::user()->id;
        $retfun->ret_fun_id = $rf->id;
        $retfun->message = $message;
        $retfun->save();

        $wf_state_sequence = WorkflowState::find($rf->wf_state_current_id)->sequence_number;
        $old_wf_state_sequence = WorkflowState::find($old->wf_state_current_id)->sequence_number;

        if ( $rf->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence > $old_wf_state_sequence ) {
            $rf->wf_records()->create($this->defaultValuesWfRecord($rf->wf_state_current_id, 1, "El usuario " . Auth::user ()->username . " Derivó el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $rf->wf_state->name . "."));
        }
        if ( $rf->wf_state_current_id != $old->wf_state_current_id && $wf_state_sequence < $old_wf_state_sequence ) {
            $rf->wf_records()->create($this->defaultValuesWfRecord($rf->wf_state_current_id, 1 , "El usuario " . Auth::user()->username . " Devolvió el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $rf->wf_state->name . " con Nota: " . request()->message . "."));
        }
        if ( $old->inbox_state == false && $rf->inbox_state == true &&  $rf->wf_state_current_id == $old->wf_state_current_id  ) {
            $rf->wf_records()->create($this->defaultValuesWfRecord($rf->wf_state_current_id, 1,'El usuario ' . Auth::user ()->username . ' Validó el trámite.'));
        }
        if ($old->inbox_state == true && $rf->inbox_state == false && $rf->wf_state_current_id == $old->wf_state_current_id  ) {
            $rf->wf_records()->create($this->defaultValuesWfRecord($rf->wf_state_current_id, 1, 'El usuario ' . Auth::user()->username . ' Canceló el trámite.'));
        }
    }
}