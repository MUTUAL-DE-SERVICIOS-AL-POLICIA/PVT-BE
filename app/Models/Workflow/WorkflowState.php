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
}
