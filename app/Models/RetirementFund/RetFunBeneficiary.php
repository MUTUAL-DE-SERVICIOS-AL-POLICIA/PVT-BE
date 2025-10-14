<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RetFunBeneficiary extends Model
{
    use SoftDeletes;

    protected $casts = [
        'percentage' => 'float',
        'amount_ret_fun' => 'float',
    ];

    public function getBirthDateAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
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
    public function legal_guardian()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunLegalGuardian', 'ret_fun_legal_guardian_beneficiary', 'ret_fun_beneficiary_id', 'ret_fun_legal_guardian_id');
    }
    public function address()
    {
        return $this->morphToMany('\Muserpol\Models\Address', 'addressable')->withTimestamps();
        // return $this->belongsToMany('\Muserpol\Models\Address', 'ret_fun_address_beneficiary');
    }
    public function testimonies()
    {
        return $this->belongsToMany('Muserpol\Models\Testimony')->withTimestamps();
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
    public function getAddress()
    {
        if ($this->address()->count()) {
            $address= $this->address()->first();
            if (isset($address->id)) {
                return 'Calle '.$address->street.' Nº '.$address->number_address . ' '.$address->zone;
            }
        }
        return 'Sin dirección';
    }
    public function getBirthDate($size = 'short')
    {
        $birth_date = Util::verifyBarDate($this->birth_date) ? Util::parseBarDate($this->birth_date) : $this->birth_date;
        return Util::getDateFormat($birth_date, $size);
    }
    public function ciWithExt()
    {
      return Util::removeSpaces(($this->identity_card ?? ''). ' ' . ($this->city_identity_card->first_shortened ?? ''));
    }
}
