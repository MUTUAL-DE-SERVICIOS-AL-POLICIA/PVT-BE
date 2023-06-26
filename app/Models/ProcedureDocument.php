<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureDocument extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    public $fillable = ['name'];
    protected $dates = ['deleted_at'];

    public function procedure_requirements()
    {
        return $this->hasMany('Muserpol\Models\ProcedureRequirement');
    }

}
