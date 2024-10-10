<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcoComDirectPayment extends Model
{

    use SoftDeletes;
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = [
        'amount',
        'voucher',
        'payment_date'
    ];
    public function ecoComMovement()
    {
        return $this->morphOne(EcoComMovement::class, 'movementable');
    }
}
