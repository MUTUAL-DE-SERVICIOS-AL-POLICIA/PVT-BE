<?php

namespace Muserpol;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    public function operation()    
    {
        return $this->belongsTo('Muserpol\Operation');
    }
    public function action()
    {
        return $this->belongsTo('Muserpol\Action');
    }
}
