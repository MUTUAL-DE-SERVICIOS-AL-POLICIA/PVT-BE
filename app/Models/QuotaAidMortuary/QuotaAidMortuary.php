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
        return $this->belongsTo('Muserpol\User');
    }
    public function procedure_modality()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureModality', 'procedure_modality_id');
    }
    public function quota_aid_procedure()
    {
        return $this->belongsTo('Muserpol\Models\QuotaAidMortuary\QuotaAidProcedure');
    }
    public function city_start()    
    {
        return $this->belongsTo('Muserpol\Models\City', 'city_start_id');
    }
    public function city_end()
    {
        return $this->belongsTo('Muserpol\Models\City', 'city_end_id');
    }
    public function quota_aid_submitted_document()
	{
		return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidSubmittedDocument');
    }
    public function quota_aid_observation()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidObservation');
    }
    public function quota_aid_beneficiary()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary');
    }
    public function workflow()
    {
        return $this->belongsTo('Muserpol\Workflow');
    }
    public function wf_state()
    {
        return $this->belongsTo('Muserpol\WfState', 'wf_state_current_id');
    }
}
