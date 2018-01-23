<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class PensionEntity extends Model
{
    protected $table = 'pension_entities';

	protected $fillable = [

		'type',
		'name'

	];

	// public function affiliates()
 //    {
 //    	return $this->hasMany('Muserpol\Affiliate');
 //    }

}
