<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class KinshipBeneficiary extends Model
{
    public function ret_fun_advisors()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunAdvisor', 'ret_fun_advisor_beneficiary', 'kinship_beneficiary_id', 'ret_fun_advisor_id');
    }

    public function quota_aid_advisors()
    {
        return $this->belongsToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor', 'quota_aid_advisor_beneficiary', 'kinship_beneficiary_id', 'quota_aid_advisor_id');
    }
}
