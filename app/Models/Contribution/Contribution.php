<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [

        'user_id',
        'affiliate_id',
        'type',
        'degree_id',
        'unit_id',
        'breakdown_id',
        'category_id',
        'month_year',
        'base_wage',
        'dignity_pension',
        'seniority_bonus',
        'study_bonus',
        'position_bonus',
        'border_bonus',
        'east_bonus',
        'public_security_bonus',
        'gain',
        'payable_liquid',
        'quotable',
        'retirement_fund',
        'mortuary_quota',
        'mortuary_aid',
        'subtotal',
        'ipc',
        'total'

    ];
    
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }

    public function degree()
    {        
        return $this->belongsTo(\Muserpol\Models\Degree::class);
    }

    public function category()
    {
        return $this->belongsTo('Muserpol\Models\Category');
    }

    public function contribution_type()
    {
        return $this->belongsTo('Muserpol\Models\Contribution\ContributionType');
    }
    public function unit()
    {
        return $this->belongsTo('Muserpol\Models\Unit');
    }
    public function breakdown()
    {
        return $this->belongsTo('Muserpol\Models\Breakdown');
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
