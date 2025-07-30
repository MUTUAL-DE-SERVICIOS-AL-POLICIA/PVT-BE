<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Hierarchy extends Model
{
	protected $fillable = [
		'code',
		'name'
	];

    public function degrees()
    {
        return $this->hasMany(Degree::class);
    }

    public function retFunProcedures()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunProcedure', 'ret_fun_procedures_hierarchies')
            ->withPivot('apply_limit')
            ->withTimestamps();
    }
}
