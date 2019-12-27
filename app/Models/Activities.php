<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Muserpol\User;

class Activities extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
