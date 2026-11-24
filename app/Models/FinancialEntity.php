<?php

namespace Muserpol\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FinancialEntity extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    public $fillable = ['name'];

    /**
     * Convertir las fechas timestamptz de PostgreSQL a timestamp
     */
    protected function asDateTime($value)
    {
        if ($value === null || $value instanceof Carbon) {
            return $value;
        }

        return Carbon::parse($value);
    }

    public function FinancialEntities()
	{
		return $this->hasMany(Affiliate::class);
    }
}