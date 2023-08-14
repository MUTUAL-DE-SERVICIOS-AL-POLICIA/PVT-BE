<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedureRequirement extends Model
{
    Use softDeletes;

    public function procedure_modality()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureModality');
    }

    public function procedure_document()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureDocument');
    }

    public function ret_fun_submitted_documents()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunSubmittedDocument');
    }
}
