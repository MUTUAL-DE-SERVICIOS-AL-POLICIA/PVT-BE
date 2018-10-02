<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    public function ret_fun_beneficiaries()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunBeneficiary')->withTimestamps();
    }
}
