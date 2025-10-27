<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetFunRefund extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'retirement_fund_id',
        'ret_fun_refund_type_id',
        'subtotal',
        'yield',
        'total',
    ];

    public function retirement_fund()
    {
        return $this->belongsTo(RetirementFund::class);
    }

    public function ret_fun_refund_type()
    {
        return $this->belongsTo(RetFunRefundType::class);
    }

    public function ret_fun_refund_amounts()
    {
        return $this->hasMany(RetFunRefundAmount::class);
    }
}
