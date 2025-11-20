<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComStateType extends Model
{
    protected $table = 'eco_com_state_types';

    public const PAGADO = 1;

    public function eco_com_states()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComStateType');
    }
}
