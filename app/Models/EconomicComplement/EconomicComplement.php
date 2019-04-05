<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class EconomicComplement extends Model
{
    // protected $table = 'economic_complements_1';
    protected $guarded = [];
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function eco_com_state()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComState');
    }
    public function eco_com_procedure()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComProcedure');
    }
    public function procedure_state()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureState');
    }
    public function city()
    {
        return $this->belongsTo('Muserpol\Models\City');
    }
    public function category()
    {
        return $this->belongsTo('Muserpol\Models\Category');
    }
    public function degree()
    {
        return $this->belongsTo('Muserpol\Models\Degree');
    }
    public function base_wage()
    {
        return $this->belongsTo('Muserpol\Models\BaseWage');
    }
    public function complementary_factor()
    {
        return $this->belongsTo('Muserpol\Models\ComplementaryFactor');
    }
    public function wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState', 'wf_current_state_id', 'id');
    }
    public function workflow()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\Workflow');
    }
    public function getBasicInfoCode()
    {
        $code = $this->id." ".($this->affiliate->id ?? null) ."\n". "Trámite Nro: ".$this->code."\nModalidad: ".$this->eco_com_modality->name."\Beneficiario: ".($this->eco_com_beneficiary->fullName() ?? null);
        $hash = crypt($code, 100);
        return array('code' => $code, 'hash'=>$hash);
        ;
    }
    /**
     *!! TODO
     */
    public function eco_com_modality()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComModality');
    }
    public function eco_com_beneficiary()
    {
        return $this->hasOne('Muserpol\Models\EconomicComplement\EcoComBeneficiary');
    }
    // public function procedure_modality()
    // {
    //     return $this->belongsTo('Muserpol\Models\ProcedureModality');
    // }
}
