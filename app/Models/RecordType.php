<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class RecordType extends Model
{
    //
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = ['name', 'display_name'];

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
