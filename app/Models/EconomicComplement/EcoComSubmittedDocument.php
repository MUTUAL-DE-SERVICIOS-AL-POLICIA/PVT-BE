<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComSubmittedDocument extends Model
{
    public function eco_com_process()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComProcess');
    }

    public function procedure_requirement()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureRequirement');
    }
}
