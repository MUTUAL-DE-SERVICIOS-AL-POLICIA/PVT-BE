<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateState extends Model
{
    // public function affiliate_state_type()
    // {
    // 	return $this->belongsTo('Muserpol\AffiliateStateType');
    // }
    public function affiliates()
    {
    	return $this->hasMany('Muserpol\Affiliate');
    }
}
