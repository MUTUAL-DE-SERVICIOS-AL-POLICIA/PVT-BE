<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EcoComLegalGuardian extends Model
{
    protected $table = 'eco_com_legal_guardians';
    public function getDueDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function economic_complement()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
    public function getDateAuthorityAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
}
