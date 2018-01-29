<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetFunApplicant extends Model
{
    use SoftDeletes;

    public function retirement_fund()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetirementFund');
    }
    public function city_identity_card()
    {
        return $this->belongsTo('Muserpol\Models\City', 'city_identity_card_id', 'id');
    }
    public function kinship()
    {
        return $this->belongsTo('Muserpol\Models\Kinship');
    }
}
