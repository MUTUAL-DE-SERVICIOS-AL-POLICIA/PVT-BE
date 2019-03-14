<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComLegalGuardian extends Model
{
    protected $table = 'eco_com_legal_guardians';
    public function economic_complement()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
}
