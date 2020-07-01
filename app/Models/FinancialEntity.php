<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialEntity extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    public $fillable = ['name'];

    public function FinancialEntities()
	{
		return $this->hasMany(Affiliate::class);
    }
}
