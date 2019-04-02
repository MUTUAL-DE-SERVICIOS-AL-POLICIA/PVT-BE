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
    public function eco_com_process()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComProcess');
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
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState', 'wf_state_current_id', 'id');
    }
    public function workflow()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\Workflow');
    }
    /**
     *!! TODO
     */
    public function eco_com_modality()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComModality');
    }
}
