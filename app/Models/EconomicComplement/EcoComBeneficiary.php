<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
use Muserpol\Models\City;

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
    public function getMarriageDateAttribute($value)
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
        return $this->morphToMany('\Muserpol\Models\Address', 'addressable')->withTimestamps();
    }
    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
    public function city_birth()
    {
        return $this->belongsTo(City::class, 'city_birth_id', 'id');
    }
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function ciWithExt()
    {
        return Util::removeSpaces($this->identity_card . ' ' .($this->city_identity_card->first_shortened ?? ''));
    }
    public function ciWithExtToBank()
    {
        return Util::removeSpaces($this->identity_card . ' ' .($this->city_identity_card->to_bank ?? ''));
    }
    public function getCivilStatus()
    {
        return Util::getCivilStatus($this->civil_status, $this->gender);
    }
}
