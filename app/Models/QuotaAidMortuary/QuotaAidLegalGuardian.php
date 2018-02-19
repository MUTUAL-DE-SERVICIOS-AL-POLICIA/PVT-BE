<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;

class QuotaAidLegalGuardian extends Model
{
    protected $table = "quota_aid_legal_guardians";
    public function city_identity_card_id()    
    {
        return $this->belongsTo('Muserpol\Models\City', 'city_identity_card_id');
    }
    public function quota_aid_beneficiary()
    {
        return $this->belongsToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary', 'quota_aid_beneficiary_legal_guardian', 'quota_aid_beneficiary_id', 'quota_aid_legal_guardian_id');
    }
}
