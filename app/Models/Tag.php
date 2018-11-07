<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $guarded =  [];
    // public function retirement_funds()
    // {
    //     return $this->morphedByMany('Muserpol\Models\RetirementFund\RetirementFund', 'taggable')->withTimestamps();
    // }
}
