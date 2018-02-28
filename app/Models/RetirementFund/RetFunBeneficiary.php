<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;

class RetFunBeneficiary extends Model
{
    public function kinship()
    {
        return $this->belongsTo('Muserpol\Models\Kinship');
    }
    
    public function city_identity_card()
    {
        return $this->belongsTo('Muserpol\Models\City');
    
    }

    public function retirement_fund()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetirementFund');
    }
    public function ret_fun_advisors()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunAdvisor','ret_fun_advisor_beneficiary','ret_fun_beneficiary_id','ret_fun_advisor_id');
    }

    /**
     * Methods
      */
    public function fullName()
    {
        $name = $this->first_name . ' ' . $this->second_name . ' ' . $this->last_name . ' ' . $this->mothers_last_name . ' ' . $this->applicant_surname_husband;
        return Util::removeSpaces($name);
    }
}
