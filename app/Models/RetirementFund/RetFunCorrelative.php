<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Muserpol\Helpers\Util;
use Illuminate\Database\Eloquent\SoftDeletes;
class RetFunCorrelative extends Model
{
    use SoftDeletes;

    public function wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState','wf_state_id');
    }
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
    public function retirement_fund()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetirementFund');
    }
}
