<?php

namespace Muserpol;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    public function procedure_modality()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureModality');
    }
    public function  wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState');
    }
}
