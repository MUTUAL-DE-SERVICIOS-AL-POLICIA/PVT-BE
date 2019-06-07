<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    public function devolution()
    {
        return $this->belongsTo(Devolution::class);
    }
    public function eco_com_procedure()
    {
        return $this->belongsTo(EcoComProcedure::class);
    }
}
