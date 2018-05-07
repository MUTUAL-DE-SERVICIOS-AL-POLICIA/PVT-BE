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
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function calcAge($text = false, $date_death = true)
    {
        if ($text) {
            return $date_death ? Util::calculateAge($this->birth_date, $this->date_death) : Util::calculateAge($this->birth_date, $date_death);
        }
        return $date_death ? Util::calculateAgeYears($this->birth_date, $this->date_death) : Util::calculateAgeYears($this->birth_date, $date_death);
    }
    public function getCivilStatus()
    {
        return Util::getCivilStatus($this->civil_status, $this->gender);
    }
}
