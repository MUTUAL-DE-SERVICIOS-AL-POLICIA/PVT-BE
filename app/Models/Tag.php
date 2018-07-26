<?php

namespace Muserpol;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function retirement_funds()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetirementFund');
    }
    public function roles()
    {
        return $this->belongsToMany('Muserpol\Models\Role');
    }
}
