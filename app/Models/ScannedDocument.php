<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class ScannedDocument extends Model
{
    protected $table = 'affiliate_scanned_documents';
    public function affiliate_folder()
    {
        return $this->belongsTo('Muserpol\Models\AffiliateFolder');
    }
    public function procedure_document()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureDocument');
    }
}
