<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateDevice extends Model
{
    protected $fillable = ['affiliate_id', 'api_token', 'device_id', 'enrolled', 'verified', 'liveness_actions', 'eco_com_procedure_id'];
    protected $primaryKey = 'affiliate_id';
    public $incrementing = false;
    protected $casts = [
        'liveness_actions' => 'array',
    ];

    public function affiliate() {
        return $this->belongsTo(Affiliate::class);
    }

    public function eco_com_procedure() {
        return $this->belongsTo(EcoComProcedure::class);
    }
}
