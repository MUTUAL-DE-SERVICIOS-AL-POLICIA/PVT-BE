<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;
use Carbon\Carbon;

class RetFunLegalGuardian extends Model
{
    /**
     * Methods
     */
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function city_identity_card()
    {
        return $this->belongsTo(\Muserpol\Models\City::class, 'city_identity_card_id', 'id');
    }
    public function getDateAuthorityAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    
}
