<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
use Muserpol\Models\City;

class EcoComLegalGuardian extends Model
{
    protected $table = 'eco_com_legal_guardians';
    public function getDueDateAttribute($value)
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
    public function getDateAuthorityAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function eco_com_legal_guardian_type()
    {
        return $this->belongsTo(EcoComLegalGuardianType::class);
    }
    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function ciWithExt()
    {
        return Util::removeSpaces($this->identity_card . ' ' .($this->city_identity_card->first_shortened ?? ''));
    }
}
