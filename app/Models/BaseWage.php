<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class BaseWage extends Model
{
	protected $dates = ['month_year'];

	protected $fillable = [
		'user_id',
		'degree_id',
		'month_year',
		'amount',
		'is_real_value'
	];

	public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

}
