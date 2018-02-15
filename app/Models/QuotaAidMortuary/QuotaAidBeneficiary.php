<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;

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
    public function address_quota_aid_beneficiary()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\AddressQuotaAidBeneficiary');
    }
    public function quota_aid_advisor_beneficiary()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisorBeneficiary');
    }
    public function quota_aid_beneficiary_legal_guardian()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary');
    }
}
