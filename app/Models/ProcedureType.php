<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureType extends Model
{
    /** Pago global */
    public const RET_FUN_PG = 1;

    /** Fondo de retiro */
    public const RET_FUN = 2;

    /** Devolución de aportes */
    public const RET_FUN_DA = 21;

    public function procedure_modalities()
    {
        return $this->hasMany('Muserpol\Models\ProcedureModality');
    }
    
    public function module()
    {
        return $this->belongsTo('Muserpol\Models\Module');
    }
}
