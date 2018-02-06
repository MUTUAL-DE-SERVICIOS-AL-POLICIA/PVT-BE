<?php

namespace Muserpol;

use Illuminate\Database\Eloquent\Model;

class RetFunProcedures extends Model
{
    protected $table = "ret_fun_procedures";

	protected $fillable = [
		'annual_yield',
        'administrative_expenses',
        'contributions_number',
        'is_enabled',
	];

	public function retirement_funds()
	{
		return $this->belongsToMany(retirement_funds::class,'city_identity_card_id','id');
    }
    
	public function affiliates_with_births()
	{
		return $this->hasMany(Affiliate::class,'city_birth_id','id');
	}

	public function adress()
	{
		return $this->belongsToMany(Address::class);
	}

	public function retirement_funds_city_start()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund','city_start_id');
	
	}
	
	public function retirement_funds_city_end()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund','city_end_id');
    }

	public function ret_fun_beneficiaries()
	{
		return $this->hasMany('Muserpol\Models\RetirementFund\RetFunBeneficiary','city_identity_card_id','id');
	}
	public function ret_fun_advisors()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunAdvisor','city_identity_card_id','id');
	}
	public function ret_fun_applicants()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunApplicant','city_identity_card_id','id');
    }
}
