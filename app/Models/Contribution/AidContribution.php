<?php

namespace Muserpol\Models\AidContribution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AidContribution extends Model
{

    use SoftDeletes; 
    
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function user()
    {
        return $this->belongsTo('Muserpol\Models\User');
    }
}
