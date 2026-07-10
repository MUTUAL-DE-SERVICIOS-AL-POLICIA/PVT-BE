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
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
    
    public function city_birth()
    {
        return $this->belongsTo(City::class, 'city_birth_id', 'id');
    }
    public function records()
    {
        return $this->morphMany(Record::class, 'recordable');
    }
    public function getBirthDateAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getMarriageDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getDueDateAttribute($value)
    {
        if(!$value){
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
    public static function updatePersonalInfo($affiliate_id, $object)
    {
        $spouse = Spouse::where('affiliate_id', $affiliate_id)->first();
        if (!$spouse) {
            $spouse = new Spouse();
            $spouse->affiliate_id = $affiliate_id;
            $spouse->registration = 0;
        }
        $spouse->user_id = Util::getAuthUser()->id;
        $spouse->identity_card = $object['identity_card'];
        $spouse->first_name = $object['first_name'];
        $spouse->second_name = $object['second_name'];
        $spouse->last_name = $object['last_name'];
        $spouse->mothers_last_name = $object['mothers_last_name'];
        if ($object['gender'] == 'F') {
            $spouse->surname_husband = $object['surname_husband'];
        }
        $spouse->birth_date = Util::verifyBarDate($object['birth_date']) ? Util::parseBarDate($object['birth_date']) : $object['birth_date'];
        $spouse->city_identity_card_id = $object['city_identity_card_id'];
        $spouse->save();
    }
    public function ciWithExt()
    {
        return Util::removeSpaces($this->identity_card . ' ' . ($this->city_identity_card->first_shortened ?? ''));
    }
    // Estado fallecido de la esposa
    public function getDeadAttribute()
    {
      return ($this->date_death != null || $this->reason_death != null || $this->death_certificate_number != null);
    }
}
