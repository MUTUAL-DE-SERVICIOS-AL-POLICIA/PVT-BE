<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    public function document()
    {
        return $this->belongsTo('Muserpol\Models\Procedure_Documents');
    }
}