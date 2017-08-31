<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class BaseWage extends Model
{
	protected $fillable = [
		'user_id',
		'degree_id',
		'month_year',
		'amount'
	];

	public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

}
