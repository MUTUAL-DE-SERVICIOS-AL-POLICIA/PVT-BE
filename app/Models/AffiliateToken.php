<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
//use Muserpol\Models\AffiliateDevice;

class AffiliateToken extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = [
        'affiliate_id',
        'api_token',
        'firebase_token'
    ];
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }

    public function affiliate_device(){
        return $this->hasOne(AffiliateDevice::class, 'affiliate_token_id', 'id');
    }
}
