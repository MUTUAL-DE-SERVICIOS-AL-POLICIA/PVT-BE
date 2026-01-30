<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetFunRefundAmount extends Model
{

    protected $fillable = [
        'ret_fun_beneficiary_id',
        'ret_fun_refund_id',
        'percentage',
        'amount',
    ];

    public function ret_fun_beneficiary()
    {
        return $this->belongsTo(RetFunBeneficiary::class);
    }

    public function ret_fun_refund()
    {
        return $this->belongsTo(RetFunRefund::class);
    }
}
