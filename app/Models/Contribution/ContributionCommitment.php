<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContributionCommitment extends Model
{

    use SoftDeletes;
    
     protected $dates = ['deleted_at'];
}
