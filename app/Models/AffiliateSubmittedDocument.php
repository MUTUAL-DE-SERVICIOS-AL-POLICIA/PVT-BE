<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateSubmittedDocument extends Model
{
    protected $guarded = [];
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
    public function procedure_document()
    {
        return $this->belongsTo(ProcedureDocument::class);
    }
}
