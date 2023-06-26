<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Kinship extends Model
{
    public $fillable = ['name'];

    public function ret_fun_beneficiaries()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunBeneficiary');
    }
    
    public function ret_fun_advisors()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunAdvisor');
    }

    public function ret_fun_applicants()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunApplicant');
    }
    public function quota_aid_advisor()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor');
    }
}
