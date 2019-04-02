<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComBeneficiary extends Model
{
    protected $table = 'eco_com_applicants';
    public function eco_com_process()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComProcess');
    }
    public function address()
    {
        return $this->belongsToMany('\Muserpol\Models\Address', 'address_eco_com_applicant', 'eco_com_applicant_id', 'address_id')->withTimestamps();
    }
}
