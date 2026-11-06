<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class BaseWage extends Model
{
    // Laravel 10 elimino $dates: las fechas se declaran en $casts.
    protected $casts = [
        'month_year' => 'datetime',
    ];
	protected $fillable = [
		'user_id',
		'degree_id',
		'month_year',
		'amount',
	];

	public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

}
