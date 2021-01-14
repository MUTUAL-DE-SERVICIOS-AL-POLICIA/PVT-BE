<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateDevice extends Model
{
    protected $fillable = ['affiliate_id', 'api_token', 'device_id', 'enrolled'];
    protected $primaryKey = 'affiliate_id';
    public $incrementing = false;

    public function affiliate() {
        return $this->belongsTo(Affiliate::class);
    }
}
