<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotaAidMortuary extends Model
{
    use SoftDeletes;

    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function user()
    {
        return $this->belongsTo('Muserpol\Models\User');
    }
    public function procedure_modality()
    {
        return $this->belongsTo('Muserpol\Models\Procedure_Modality', 'procedure_modalities_id');
    }
    public function quota_aid_procedure()
    {
        return $this->belongsTo('Muserpol\Models\quota_aid_procedures');
    }
    public function city_start()
    {
        return $this-belongsTo('Muserpol\Models\city', 'city_start_id');
    }
    public function city_end()
    {
        return $this-belongsTo('Muserpol\Models\city', 'city_end_id');
    }
    public function quota_aid_beneficiaries()
	{
		return $this->hasMany('Muserpol\Models\RetirementFund\QuotaAidBeneficiaries');
    }
}
