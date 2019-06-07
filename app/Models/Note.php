<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $guarded =  [];

    public function annotable()
    {
        return $this->morphTo();
    }
}
