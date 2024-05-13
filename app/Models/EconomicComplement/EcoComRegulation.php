<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComRegulation extends Model
{
    public function eco_com_procedure()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComProcedure', 'replica_eco_com_procedure_id');
    }
    public function eco_com_fixed_pensions()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComFixedPension');
    }
}
