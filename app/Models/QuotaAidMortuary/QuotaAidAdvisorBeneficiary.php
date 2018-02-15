<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;

class QuotaAidAdvisorBeneficiary extends Model
{
    protected $table = "quota_aid_advisor_beneficiary";
    public function quota_aid_beneficiary()
    {
        return $this->belongsTo('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary');
    }
    public function quota_aid_advisor()
    {
        return $this->belongsTo('Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor');
    }

}
