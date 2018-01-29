<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $table = "addresses";

    public function affiliate()
    {
    	return $this->belongsToMany(Affiliate::class);
    }
}
