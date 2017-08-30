<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;


class City extends Model
{
	protected $table = "cities";

	public function affiliates_with_identity_cards()
	{
		return $this->hasMany(Affiliate::class,'city_identity_card_id','id');
	}
	public function affiliates_with_births()
	{
		return $this->hasMany(Affiliate::class,'city_birth_id','id');
	}

	public function adress()
	{
		return $this->belongsToMany(Address::class);
	}

}
