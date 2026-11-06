<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContributionCommitment extends Model
{

    use SoftDeletes;
    
    // Laravel 10 elimino $dates: las fechas se declaran en $casts.
    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
