<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetirementFund extends Model
{
    use SoftDeletes;

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
        return $this->belongsTo('Muserpol\Models\ProcedureModality', 'procedure_modality_id');
    }

    public function ret_fun_procedure()
    {
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetFunProcedure');
    }

    public function city_start()
    {
        return $this->belongsTo('Muserpol\Models\City','city_start_id');
    }

    public function city_end()
    {
        return $this->belongsTo('Muserpol\Models\City','city_end_id');
    }
    
    public function ret_fun_beneficiaries()
	{
		return $this->hasMany('Muserpol\Models\RetirementFund\RetFunBeneficiary');
    }

    public function ret_fun_applicant()
	{
		return $this->hasOne('Muserpol\Models\RetirementFund\RetFunApplicant');
    }
    public function discount_types()
    {
        return $this->belongsToMany('Muserpol\Models\DiscountType')->withPivot('amount');
    }    
    public function ret_fun_state(){
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetFunState','ret_fun_state_id');
    }
}
