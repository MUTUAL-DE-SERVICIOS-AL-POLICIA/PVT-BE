<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateFolder extends Model
{
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    
    public function procedure_modality()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureModality');
    }
}
