<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureModality extends Model
{
    public function procedure_type()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureType');
    }

    public function procedure_requirements()
    {
        return $this->hasMany('Muserpol\Models\ProcedureRequirement');
    }

    public function retirement_funds()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund','procedure_modalities_id');
    }

    public function affiliate_folders()
    {
        return $this->hasMany('Muserpol\Models\AffiliateFolder');
    }
    public function quota_aid_mortuaries()
    {
        return $this->hasMany('Muserpol\Models\'QuotaAidMortuaries\QuotaAidMortuary');
    }

    

}
