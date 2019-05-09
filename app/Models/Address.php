<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    // use SoftDeletes;

    protected $table = "addresses";

    protected $attributes = array(
        'city_address_id' => null,
        'zone' => null,
        'street' => null,
        'number_address' => null,
    );

    public function affiliate()
    {
    	return $this->belongsToMany(Affiliate::class);
    }

    public function quota_aid_beneficiaries() 
    {
        return $this->belongToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary', 'address_quota_aid_beneficiary', 'quota_aid_beneficiary_id', 'address_id');
    }
    public function ret_fun_beneficiary()
    {
        return $this->belongsToMany('Muserpol\Models\RetirementFund\RetFunBeneficiary','ret_fun_address_beneficiary');
    }
    public function eco_com_beneficiary()
    {
        return $this->belongsToMany('Muserpol\Models\EconomicComplement\EcoComBeneficiary','address_eco_com_beneficiary')->withTimestamps();
    }
    public function city()
    {
        return $this->belongsTo('Muserpol\Models\City','city_address_id','id');
    }
}
