<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

	protected $fillable = [
		'from',
		'to',
		'name',
		'percentage'
	];

	public function affiliates()
	{
		return $this->hasMany(Affiliate::class);
	}

	public function contributions()
    {
    	return $this->hasMany(RetirementFund\Contribution::class);
    }


}
