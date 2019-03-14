<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComBeneficiary extends Model
{
    protected $table = 'eco_com_beneficiaries';
    public function eco_com_process()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComProcess');
    }
}
