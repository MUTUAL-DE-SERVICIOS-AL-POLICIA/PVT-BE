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
    public function affiliate()
    {
        return $this->belongsTo("Muserpol\Models\Affiliate");
    }
    public function payment_type()
    {
        return $this->belongsTo("Muserpol\Models\PaymentType");
    }
    public function voucher_type()
    {
        return $this->belongsTo("Muserpol\Models\VoucherType");
    }
    public function user()
    {
        return $this->belongsTo("Muserpol\User");
    }
    public function type() {
        return $this->belongsTo('Muserpol\Models\VoucherType','voucher_type_id');
    }
    public function contributions()
    {
        return $this->belongsToMany('Muserpol\Models\Contribution\Contribution')->withTimestamps();
    }
    public function aid_contributions()
    {
        return $this->belongsToMany('Muserpol\Models\Contribution\AidContribution')->withTimestamps();
    }    

    public function payable () {
        return $this->morphTo();
    }
}
