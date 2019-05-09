<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComProcess extends Model
{
    protected $table = 'eco_com_processes';
    public function economic_complements()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
    public function eco_com_beneficiary()
    {
        return $this->hasOne('Muserpol\Models\EconomicComplement\EcoComBeneficiary');
    }
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
    public function procedure_modality()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureModality');
    }
    public function pension_entity()
    {
        return $this->belongsTo('Muserpol\Models\PensionEntity');
    }
    public function hasEconomicComplementWithProcedure($eco_com_procedure_id)
    {
        return !! $this->economic_complements()->where('eco_com_procedure_id',$eco_com_procedure_id)->get()->count();
    }
}
