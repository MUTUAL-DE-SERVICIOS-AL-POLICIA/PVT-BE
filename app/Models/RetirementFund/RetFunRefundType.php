<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\Contribution\ContributionType;

class RetFunRefundType extends Model
{
    protected $fillable = [
        'percentage_yield',
        'contributions_type_id',
    ];

    public function contribution_type()
    {
        return $this->belongsTo(ContributionType::class);
    }
}
