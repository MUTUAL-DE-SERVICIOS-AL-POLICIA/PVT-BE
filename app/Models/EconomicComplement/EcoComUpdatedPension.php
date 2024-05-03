<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComUpdatedPension extends Model
{
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
    public function economic_complement()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
}
