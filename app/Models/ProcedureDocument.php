<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedureDocument extends Model
{
    public $timestamps = true;
    public $fillable = ['name'];

    public function procedure_requirements()
    {
        return $this->hasMany('Muserpol\Models\ProcedureRequirement');
    }

}
