<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class Spouse extends Model
{
    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
}
