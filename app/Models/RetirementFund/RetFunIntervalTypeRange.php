<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

class RetFunIntervalTypeRange extends Model
{
    public function interval_type()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\IntervalType');
    }

    public function retirement_fund()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetirementFund');
    }
}
