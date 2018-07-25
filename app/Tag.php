<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function retirement_funds()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetirementFund');
    }
}
