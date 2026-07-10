<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\BaseWage;

class EcoComFixedPension extends Model
{
    protected $filliable= [
        'user_id',
        'eco_com_procedure_id',
        'rent_type',
        'aps_total_cc',
        'aps_total_fsa',
        'aps_total_fs',
        'aps_disability',
        'aps_total_death',
        'sub_total_rent',
        'reimbursement',
        'dignity_pension',
        'total_rent',
    ];
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function eco_com_regulation()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComRegulation');
    }
    public function eco_com_procedure()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComProcedure');
    }
    public function economic_complements()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
    public function eco_com_rent()
    {
        return $this->belongsTo(EcoComRent::class);
    }
    public function base_wage()
    {
        return $this->belongsTo(BaseWage::class);
    }
    public function calculateTotalRentAps()
    {
        $this->total_rent = $this->aps_total_death +  $this->aps_disability + $this->aps_total_cc + $this->aps_total_fsa + $this->aps_total_fs;
        $this->save();
    }
}
