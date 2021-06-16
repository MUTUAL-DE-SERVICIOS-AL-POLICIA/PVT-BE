<?php
namespace Muserpol\Observers;
use Muserpol\Models\RetirementFund\RetFunObservation;
use Muserpol\Models\RetirementFund\RetFunRecord;
use Auth;
use Log;
use Carbon\Carbon;

class RetirementFundObservationObserver{

    public function created(RetFunObservation $rf_observation)
    {
        $retfun = new RetFunRecord;
        $retfun->user_id = Auth::user()->id;
        $retfun->ret_fun_id = $rf_observation->id;
        $retfun->message = 'El usuario '.Auth::user()->username.' creo la observación '.$rf_observation->observation_type->name.'.';
        $retfun->save();
    }
}