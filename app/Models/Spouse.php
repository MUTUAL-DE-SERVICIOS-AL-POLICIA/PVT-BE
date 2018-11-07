<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;
use Carbon\Carbon;

class Spouse extends Model
{
    protected $attributes = array(
        'affiliate_id' => null,
        'user_id' => null,
        'registration' => null,
        'identity_card' => null,
        'first_name' => null,
        'second_name' => null,
        'last_name' => null,
        'mothers_last_name' => null,
        'surname_husband' => null,
        'birth_date' => null,
        'city_birth_id' => null,
        'city_identity_card_id' => null,
    );
    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
    
    public function city_birth()
    {
        return $this->belongsTo(City::class, 'city_birth_id', 'id');
    }
    public function getBirthDateAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getDateDeathAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getBirthDate($size = 'short')
    {
        $birth_date = Util::verifyBarDate($this->birth_date) ? Util::parseBarDate($this->birth_date) : $this->birth_date;
        return Util::getDateFormat($birth_date, $size);
    }
    public function getDateDeath($size = 'short')
    {
        $date_death = Util::verifyBarDate($this->date_death) ? Util::parseBarDate($this->date_death) : $this->date_death;
        return Util::getDateFormat($date_death, $size);
    }
    /**
     * Methods
     */
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function getCivilStatus()
    {
        return Util::getCivilStatus($this->civil_status, $this->gender);
    }
}
