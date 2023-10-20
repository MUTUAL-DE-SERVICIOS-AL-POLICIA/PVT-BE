<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
use Muserpol\Models\City;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcoComOncePayment extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    //protected $table = 'eco_com_applicant_originals';
    public $fillable = [
        'economic_complement_id',
        'type',
        'identity_card',
        'last_name',
        'mothers_last_name',
        'first_name',
        'second_name',
        'surname_husband',
        'birth_date',
        'nua',
        'gender',
        'civil_status',
        'phone_number',
        'cell_phone_number',
        'date_death',
        'reason_date',
        'death_certificate_number',
        'city_birth_id',
        'due_date',
        'is_duedate_undefined'
    ];
    use SoftDeletes;

    public function getDueDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getBirthDateAttribute($value)
    {
        if(!$value){
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
    public function economic_complement()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
    public function address()
    {
        return $this->morphToMany('\Muserpol\Models\Address', 'addressable')->withTimestamps();
    }
    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
    public function city_birth()
    {
        return $this->belongsTo(City::class, 'city_birth_id', 'id');
    }
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function ciWithExt()
    {
        return Util::removeSpaces($this->identity_card . ' ' .($this->city_identity_card->first_shortened ?? ''));
    }
    public function ciWithExtToBank()
    {
        return Util::removeSpaces($this->identity_card . ' ' .($this->city_identity_card->to_bank ?? ''));
    }
    public function getCivilStatus()
    {
        return Util::getCivilStatus($this->civil_status, $this->gender);
    }
}
