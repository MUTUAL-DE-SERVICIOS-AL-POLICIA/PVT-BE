<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

class RetFunRefund extends Model
{
    protected $fillable = [
        'retirement_fund_id',
        'ret_fun_refund_type_id',
        'subtotal',
        'yield',
        'total',
    ];
}
