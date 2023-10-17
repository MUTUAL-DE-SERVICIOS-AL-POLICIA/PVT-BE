<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;

class QuotaAidSubmittedDocument extends Model
{
    //
    public function quota_aid_mortuary()
    {
        return $this->belongsTo('Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary');
    }
    public function procedure_requirement()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureRequirement')->withTrashed();
    }
}
