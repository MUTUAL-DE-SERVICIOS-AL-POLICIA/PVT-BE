<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;

class ContributionProcess extends Model
{
    protected $guarded = [];
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function workflow()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\Workflow');
    }
    public function contributions()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\Contribution', 'quotable')->withTimestamps();
    }
    public function aid_contributions()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\AidContribution', 'quotable')->withTimestamps();
    }
    public function voucher() {

    }
    public function wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState', 'wf_state_current_id', 'id');
    }
    public function tags()
    {
        return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id', 'date'])->withTimestamps();
    }
    public function wf_records()
    {
        return $this->morphMany('Muserpol\Models\Workflow\WorkflowRecord', 'recordable');
    }
    public function direct_contribution()
    {
        return $this->belongsTo('Muserpol\Models\Contribution\DirectContribution');
    }
}
