<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ObservationType;

class Devolution extends Model
{
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
    public function observation_type()
    {
        return $this->belongsTo(ObservationType::class);
    }
    public function dues()
    {
        return $this->hasMany(Due::class);
    }
    public function eco_com_procedures()
    {
        return $this->belongsToMany(EcoComProcedure::class);
    }
    public function totalAmountProcedures($eco_com_procedure_ids = [])
    {
        if (!(sizeof($eco_com_procedure_ids) > 0)) {
            $eco_com_procedure_ids = $this->dues->pluck('eco_com_procedure_id');
        }
        $sum = $this->dues()->whereIn('eco_com_procedure_id', $eco_com_procedure_ids)->get()->sum('amount');
        return str_replace(",",".","".$sum);
    }
}
