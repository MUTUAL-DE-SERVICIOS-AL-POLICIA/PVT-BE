<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

class RetFunTemplate extends Model
{
    public function ret_fun_procedure()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetFunProcedure');
    }
    public function  wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState');
    }
}
