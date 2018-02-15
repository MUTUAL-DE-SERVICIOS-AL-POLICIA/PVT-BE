<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;

class AddressQuotaAidBeneficiary extends Model
{
    //
    public function quota_aid_beneficiary()
    {
        return $this->belongsTo('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary');
    }
    public function address()
    {
        return $this->belongsTo('Muserpol\Models\Address');
    } 
}
