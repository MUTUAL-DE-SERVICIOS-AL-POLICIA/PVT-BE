<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureType extends Model
{
    public function procedure_modalities()
    {
        return $this->hasMany('Muserpol\Models\ProcedureModality');
    }
    
    public function module()
    {
        return $this->belongsTo('Muserpol\Models\Module');
    }

}
