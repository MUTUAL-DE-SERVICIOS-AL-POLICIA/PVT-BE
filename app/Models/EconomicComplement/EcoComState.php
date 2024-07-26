<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComState extends Model
{
    protected $table = 'eco_com_states';
    public $timestamps = false;
    public function eco_com_state_type()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComStateType');
    }
}
