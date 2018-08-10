<?php

namespace Muserpol\Observers;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunRecord;
use Auth;
use Log;
use Carbon\Carbon;
use Muserpol\Models\Workflow\WorkflowRecord;
class RetirementFundObserver
{
    public function created(RetirementFund $rf)
    {
        $retfun = new RetFunRecord;
        $retfun->user_id = Auth::user()->id;
        $retfun->ret_fun_id = $rf->id;
        $retfun->message = 'El usuario '.Auth::user()->username. ' creo el Trámite '.$rf->code.' con la modalidad '.$rf->procedure_modality->name.' '.Carbon::now();
        $retfun->save();

        // Log::info('se creo el tramite con el id '.$retfun->id);
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
        if ( $rf->wf_state_current_id != $old->wf_state_current_id ) {
            $wf_record = new WorkflowRecord;
            $wf_record->user_id = Auth::user()->id;
            $wf_record->wf_state_id = $rf->wf_state_current_id;
            $wf_record->ret_fun_id = $rf->id;
            $wf_record->date = Carbon::now();
            $wf_record->record_type_id = 1;
            $wf_record->message = "El usuario " . Auth::user()->username . " derivo el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $rf->wf_state->name . ".";
            $wf_record->save();
        }
        if ( $old->inbox_state == false && $rf->inbox_state == true &&  $rf->wf_state_current_id == $old->wf_state_current_id  ) {
            $wf_record = new WorkflowRecord;
            $wf_record->user_id = Auth::user()->id;
            $wf_record->wf_state_id = $rf->wf_state_current_id;
            $wf_record->ret_fun_id = $rf->id;
            $wf_record->date = Carbon::now();
            $wf_record->record_type_id = 1;
            $wf_record->message =  'El usuario ' . Auth::user()->username . ' Valido el trámite.';
            $wf_record->save();
        }
        if ($old->inbox_state == true && $rf->inbox_state == false && $rf->wf_state_current_id == $old->wf_state_current_id  ) {
            $wf_record = new WorkflowRecord;
            $wf_record->user_id = Auth::user()->id;
            $wf_record->wf_state_id = $rf->wf_state_current_id;
            $wf_record->ret_fun_id = $rf->id;
            $wf_record->date = Carbon::now();
            $wf_record->record_type_id = 1;
            $wf_record->message =  'El usuario ' . Auth::user()->username . ' cancelo el trámite.';
            $wf_record->save();
        }
    }
}