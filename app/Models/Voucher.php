<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'user_id',
        'affiliate_id',
        'voucher_type_id',
        'code',
        'concept',
        'total',
        'payment_date'
    ];
}
