<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reimbursement extends Model
{

    use SoftDeletes;

    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }    
    
}
