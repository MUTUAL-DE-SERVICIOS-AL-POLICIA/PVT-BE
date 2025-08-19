<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureType extends Model
{
    /**
     * Fondo de Retiro Pago Global
     *
     * @var int
     */
    public const RET_FUN_PG = 1; // Pago global
    /**
     * Fondo de Retiro Devolución de Aportes
     *
     * @var int
     */
    public const RET_FUN_DP = 21; // Devolución de aportes

    public function procedure_modalities()
    {
        return $this->hasMany('Muserpol\Models\ProcedureModality');
    }
    
    public function module()
    {
        return $this->belongsTo('Muserpol\Models\Module');
    }

    public function modalities()
    {
        return $this->hasMany('Muserpol\Models\ProcedureModality');
    }
}
