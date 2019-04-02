<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComModality extends Model
{
    public function eco_com_type()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComType');
    }
}
