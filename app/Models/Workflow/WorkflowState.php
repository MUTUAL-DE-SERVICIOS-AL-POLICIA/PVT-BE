<?php

namespace Muserpol\Models\Workflow;

use Illuminate\Database\Eloquent\Model;

class WorkflowState extends Model
{
    protected $table = "wf_states";
    public function retirement_funds()
    {
        return $this->hasMany(RetirementFund::class, 'wf_state_current_id', 'id');
    }
    public function tags()
    {
        return $this->belongsToMany('Muserpol\Models\Tag', 'tag_wf_state','wf_state_id');
    }
    public function rol()
    {
        return $this->belongsTo('Muserpol\Models\Role', 'role_id');
    }
}
