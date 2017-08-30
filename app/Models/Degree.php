<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
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
}
