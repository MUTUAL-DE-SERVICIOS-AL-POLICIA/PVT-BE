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
    public function address() //aca
    {
        return $this->belongToMany('Muserpol\Models\Address', 'address_quota_aid_beneficiary', 'quota_aid_beneficiary_id', 'address_id');
    }
    public function quota_aid_advisors()
    {
        return $this->belongsToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor', 'quota_aid_advisor_beneficiary', 'quota_aid_beneficiary_id', 'quota_aid_advisor_id');
    }
    public function quota_aid_legal_guardians()
    {
        return $this->belongsToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidLegalGuardian', 'quota_aid_beneficiary_legal_guardian', 'quota_aid_beneficiary_id', 'quota_aid_legal_guardian_id');
    }
}
