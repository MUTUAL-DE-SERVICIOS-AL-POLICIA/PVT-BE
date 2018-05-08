<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

class RetFunRecord extends Model
{
    //
    protected $table = "ret_fun_records";
    public function retirement_fund()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetirementFund');
    }
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }

}
