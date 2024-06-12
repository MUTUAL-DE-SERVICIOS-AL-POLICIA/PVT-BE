<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcoComMovement extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'affiliate_id',
        'movement_id',
        'movement_type',
        'amount',
        'balance',
    ];
    public function movementable()
    {
        return $this->morphTo();
    }
}
