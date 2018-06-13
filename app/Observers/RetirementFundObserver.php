<?php

namespace Muserpol\Observers;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunRecord;
use Auth;
use Log;
use Carbon\Carbon;
class RetirementFundObserver
{
    public function created(RetirementFund $rf)
    {
        $retfun = new RetFunRecord;
        $retfun->user_id = Auth::user()->id;
        $retfun->ret_fun_id = $rf->id;
        $retfun->message = 'El usuario '.Auth::user()->username. ' creo el Trámite '.$rf->code.' con la modalidad'.$rf->procedure_modality->name.' '.Carbon::now();
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

    }
}