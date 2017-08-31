<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{
    use SoftDeletes;
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }

    public function degree()
    {
        return $this->belongsTo('Muserpol\Models\Degree');
    }

    public function category()
    {
        return $this->belongsTo('Muserpol\Models\Category');
    }
}
