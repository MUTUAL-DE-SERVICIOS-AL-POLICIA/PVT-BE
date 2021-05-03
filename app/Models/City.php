<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;


class City extends Model
{
    protected $table = "cities";
    public $timestamps = false;
	protected $fillable = [
		'name',
		'shortened'
    ];
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'company_phones' => 'array',
        'company_cellphones' => 'array'
    ];

	public function affiliates_with_identity_cards()
	{
		return $this->hasMany(Affiliate::class,'city_identity_card_id','id');
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
	public function quota_aid_mortuaries()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary', 'city_identity_card_id','id');
	}
	public function quota_aid_advisor()
	{
		return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor', 'city_identity_card_id','id');
	}

}
