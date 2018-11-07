<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	public $timestamps = false;
	protected $fillable = [
		'name',
		'description'
	];
	public function procedure_types()
	{
		return $this->hasMany('Muserpol\Models\ProcedureType');
	}
}
