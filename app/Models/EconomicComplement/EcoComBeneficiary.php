<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Muserpol\Helpers\Util;

class EcoComBeneficiary extends Model
{
    protected $table = 'eco_com_applicants';
    public function getDueDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getBirthDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function economic_complement()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
    public function address()
    {
        return $this->belongsToMany('\Muserpol\Models\Address', 'address_eco_com_applicant', 'eco_com_applicant_id', 'address_id')->withTimestamps();
    }
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function ciWithExt()
    {
        return Util::removeSpaces($this->identity_card . ' ' .($this->city_identity_card->first_shortened ?? ''));
    }
}
