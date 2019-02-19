<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherType extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'name'
    ];
}
