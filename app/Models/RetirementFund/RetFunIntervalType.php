<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

class RetFunIntervalType extends Model
{
    public function ret_fun_interval_type_ranges()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunIntevalTypeRange');
    }
}
