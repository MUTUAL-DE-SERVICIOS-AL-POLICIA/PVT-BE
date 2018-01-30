<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class ScannedDocument extends Model
{
    public function affiliate_folder()
    {
        return $this->belongsTo('Muserpol\Models\AffiliateFolder');
    }
    public function procedure_document()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureDocument');
    }
}
