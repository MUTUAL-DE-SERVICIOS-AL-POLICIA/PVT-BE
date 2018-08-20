<?php

namespace Muserpol\Models\Workflow;

use Illuminate\Database\Eloquent\Model;

class WorkflowSequence extends Model
{
    protected $table = "wf_sequences";
    protected $fillable = ['workflow_id', 'wf_state_current_id', 'wf_state_next_id', 'action'];
}
