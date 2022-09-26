<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;

class ContributionPassive extends Model
{   public $timestamps = true;
    public $fillable = [
     'user_id',
     'affiliate_id',
     'month_year',
     'quotable',
     'rent_pension',
     'dignity_rent',
     'interest',
     'total',
     'affiliate_rent_class',
     'contribution_state_id',
     'contributionable_type',
     'contributionable_id',
    ];
}
