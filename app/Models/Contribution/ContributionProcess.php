<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;

class ContributionProcess extends Model
{
    protected $guarded = [];
    public function contributions()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\Contribution', 'quotable')->withTimestamps();
    }
    public function aid_contributions()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\AidContribution', 'quotable')->withTimestamps();
    }
    public function tags()
    {
        return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id', 'date'])->withTimestamps();
    }
    public function wf_records()
    {
        return $this->morphMany('Muserpol\Models\Workflow\WorkflowRecord', 'recordable');
    }
}
