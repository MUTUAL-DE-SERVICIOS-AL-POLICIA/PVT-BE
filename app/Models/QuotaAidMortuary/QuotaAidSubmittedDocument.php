<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotaAidSubmittedDocument extends Model
{
    use SoftDeletes;
    protected $table = "quota_aid_submitted_documents";
    public function quota_aid_mortuary()
    {
        return $this->belongsTo(QuotaAidMortuary::class);
    }
    public function procedure_requirement()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureRequirement')->withTrashed();
    }
}
