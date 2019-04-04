<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
}
