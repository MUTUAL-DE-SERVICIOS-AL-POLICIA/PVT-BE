<?php

namespace Muserpol\Models\Workflow;

use Illuminate\Database\Eloquent\Model;

class WorkflowRecord extends Model
{
    protected $table = "wf_records";

    public function recordable()
    {
        return $this->morphTo();
    }
}
