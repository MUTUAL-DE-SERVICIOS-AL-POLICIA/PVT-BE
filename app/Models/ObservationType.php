<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class ObservationType extends Model
{
    //
    protected $table = 'observation_types';

    public function ret_fun_observation()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetFunObservation');
    }
}
