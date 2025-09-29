<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

class RetFunRefundType extends Model
{
    protected $fillable = [
        'percentage_yield',
        'contributions_type_id',
    ];
}
