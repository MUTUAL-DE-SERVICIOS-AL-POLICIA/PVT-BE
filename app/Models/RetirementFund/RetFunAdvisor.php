<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;
use Carbon\Carbon;

class RetFunAdvisor extends Model
{
    public function city_identity_card()
    {
        return $this->belongsTo('Muserpol\Models\City','city_identity_card_id','id');
    }

    public function kinship()
    {
        return $this->belongsTo('Muserpol\Models\Kinship');
    }

    public function kinship_beneficiaries($beneficiary_id)
    {
        return $this->belongsToMany('Muserpol\Models\KinshipBeneficiary', 'ret_fun_advisor_beneficiary', 'ret_fun_advisor_id', 'kinship_beneficiary_id')->wherePivot('ret_fun_beneficiary_id', $beneficiary_id)->limit(1);
    }
    public function ret_fun_beneficiaries()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunBeneficiary','ret_fun_advisor_beneficiary','ret_fun_advisor_id','ret_fun_beneficiary_id');
    }

    public function getBirthDateAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getResolutionDateAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    /**
     * Methods
     */
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
}
