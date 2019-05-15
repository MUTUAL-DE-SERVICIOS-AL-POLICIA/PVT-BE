<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\ProcedureModality;

class EcoComModality extends Model
{
    public $timestamps = false;
    public function procedure_modality()
    {
        return $this->belongsTo(ProcedureModality::class);
    }
}
