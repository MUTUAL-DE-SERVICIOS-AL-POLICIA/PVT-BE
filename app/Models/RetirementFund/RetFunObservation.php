<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

class RetFunObservation extends Model
{
    //
    protected $table = "ret_fun_observations";
    
    public function ret_fun()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetirementFund');
    }
}
