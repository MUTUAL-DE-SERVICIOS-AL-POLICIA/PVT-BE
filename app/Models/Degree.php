<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    protected $fillable = [
        'hierarchy_id',
        'code',
        'name',
        'shortened'
    ];

    public function affiliates()
    {
        return $this->hasMany(Affiliate::class);
    }

    public function contributions()
    {
    	return $this->hasMany(RetirementFund\Contribution::class);
    }

    public function hierarchy()
    {
        return $this->belongsTo(Hierarchy::class);
    }

    public function base_wages()
    {
        return $this->hasMany(BaseWage::class);
    }
}
