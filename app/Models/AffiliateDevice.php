<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\AffiliateToken;

class AffiliateDevice extends Model
{
    protected $fillable = ['enrolled', 'verified', 'liveness_actions', 'eco_com_procedure_id', 'affiliate_token_id'];
    protected $primaryKey = 'affiliate_token_id';
    public $incrementing = false;
    protected $casts = [
        'liveness_actions' => 'array',
    ];

    // public function affiliate() {
    //     return $this->belongsTo(Affiliate::class);
    // }

    public function eco_com_procedure() {
        return $this->belongsTo(EcoComProcedure::class);
    }

    public function affiliate_token(){
        return $this->belongsTo(AffiliateToken::class);
    }
}
