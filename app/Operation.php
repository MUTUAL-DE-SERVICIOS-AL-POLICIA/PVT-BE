<?php

namespace Muserpol;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    //
    public function module()
    {
        return $this->belongsTo('Muserpol\Models\Module');
    }  
}
