<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComSubmittedDocument extends Model
{
    protected $table = "eco_com_submitted_documents";
    public function economic_complement()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EconomicComplement');
    }

    public function procedure_requirement()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureRequirement');
    }
}
