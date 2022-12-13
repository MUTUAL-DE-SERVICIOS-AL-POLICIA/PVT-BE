<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AidContribution extends Model
{
    protected $table = "contribution_passives";

    use SoftDeletes; 

    protected $fillable = [
        'user_id',
        'affiliate_id',
        'month_year',
        'type',
        'quotable',
        'rent',
        'dignity_rent',
        'total',
        'affiate_rent_class',
        'contribution_type_motuary_id',
        'contribution_state_id',
        'contributionable_type',
        'contributionable_id'
    ];
    
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function user()
    {
        return $this->belongsTo('Muserpol\Models\User');
    }
    public function voucher()
    {
        return $this->belongsToMany('Muserpol\Models\Voucher')->withTimestamps();
    }
    public function contribution_process()
    {
        return $this->morphToMany('Muserpol\Models\Contribution\ContributionProcess', 'quotable')->withTimestamps();
    }
}
