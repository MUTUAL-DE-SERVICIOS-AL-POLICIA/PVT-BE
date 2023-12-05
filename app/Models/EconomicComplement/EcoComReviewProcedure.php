<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComReviewProcedure extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = [
        'review_procedure_id',
        'economic_complement_id',
        'user_id',
        'is_valid'
    ];

    public function review_procedure()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\ReviewProcedure');
    }
    public function economic_complement()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
}
