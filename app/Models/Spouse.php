<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;

class Spouse extends Model
{
    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
    
    public function city_birth()
    {
        return $this->belongsTo(City::class, 'city_birth_id', 'id');
    }

    /**
     * Methods
     */
    public function fullName()
    {
        $name = $this->first_name . ' ' . $this->second_name . ' ' . $this->last_name . ' ' . $this->mothers_last_name . ' ' . $this->applicant_surname_husband;
        return Util::removeSpaces($name);
    }
}
