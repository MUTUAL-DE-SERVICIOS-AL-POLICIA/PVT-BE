<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	protected $fillable = [
		'name',
		'description'
	];   
}
