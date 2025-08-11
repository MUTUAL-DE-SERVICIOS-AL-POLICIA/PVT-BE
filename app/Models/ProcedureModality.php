<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\EconomicComplement\EcoComModality;

class ProcedureModality extends Model
{
    public $timestamps = false;

    public function eco_com_modalities()
    {
        return $this->hasMany(EcoComModality::class);
    }
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
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund','procedure_modality_id');
    }

    public function ret_fun_procedures()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunProcedure', 'ret_fun_procedures_modalities')
            ->withPivot('annual_percentage_yield')
            ->withTimestamps();
    }

    public function affiliate_folders()
    {
        return $this->hasMany('Muserpol\Models\AffiliateFolder');
    }
    public function quota_aid_mortuaries()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary', 'procedure_modality_id');
    }

}
