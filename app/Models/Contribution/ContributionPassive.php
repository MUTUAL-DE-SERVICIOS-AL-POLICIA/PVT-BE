<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContributionPassive extends Model
{   
    use SoftDeletes;
    // Laravel 10 elimino $dates: las fechas se declaran en $casts.
    protected $casts = [
        'deleted_at' => 'datetime',
    ];
    public $timestamps = true;
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
