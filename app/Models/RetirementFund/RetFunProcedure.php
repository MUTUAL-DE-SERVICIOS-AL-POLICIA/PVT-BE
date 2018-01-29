<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

class RetFunProcedure extends Model
{
    public function retirement_funds()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund');
    }
}
