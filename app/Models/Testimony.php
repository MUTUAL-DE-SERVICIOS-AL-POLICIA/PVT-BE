<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    public function ret_fun_beneficiaries()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunBeneficiary')->withTimestamps();
    }
    public function quota_aid_beneficiaries()
    {
        return $this->belongsToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary')->withTimestamps();
    }
}
