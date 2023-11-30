<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class ReviewProcedure extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = [
        'name',
        'active'
    ];
    public function eco_com_review_procedures()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComReviewProcedure');
    }
}
