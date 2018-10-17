<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;

class QuotaAidBeneficiary extends Model
{
    //
    public function quota_aid_mortuary()
    {
        return $this->belongsTo('Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary');
    }
    public function city_identity_card()    
    {
        return $this->belongsTo('Muserpol\Models\City', 'city_identity_card_id');
    }
    public function kinship()
    {
        return $this->belongsTo('Muserpol\Models\Kinship');
    }
    public function address() //aca
    {
        return $this->belongsToMany('Muserpol\Models\Address', 'address_quota_aid_beneficiary');
    }
    public function quota_aid_advisors()
    {
        return $this->belongsToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor', 'quota_aid_advisor_beneficiary', 'quota_aid_beneficiary_id', 'quota_aid_advisor_id');
    }
    public function quota_aid_legal_guardians()
    {
        return $this->belongsToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidLegalGuardian', 'quota_aid_beneficiary_legal_guardian', 'quota_aid_beneficiary_id', 'quota_aid_legal_guardian_id');
    }
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function getAddress()
    {
        if ($this->address()->count()) {
            $address = $this->address()->first();
            if (isset($address->id)) {
                return 'Calle ' . $address->street . ' Nº ' . $address->number_address . ' ' . $address->zone;
            }
        }
        return 'Sin dirección';
    }
    public function testimonies()
    {
        return $this->belongsToMany('Muserpol\Models\Testimony')->withTimestamps();
    }
}
