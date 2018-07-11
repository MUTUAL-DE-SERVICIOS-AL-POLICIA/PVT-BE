<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class InfoLoan extends Model
{        
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    
    public function affiliate_guarantor()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate', 'affiliate_guarantor_id');
    }
}
