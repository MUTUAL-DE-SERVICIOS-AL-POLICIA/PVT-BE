<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class BaseWage extends Model
{
	public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

}
