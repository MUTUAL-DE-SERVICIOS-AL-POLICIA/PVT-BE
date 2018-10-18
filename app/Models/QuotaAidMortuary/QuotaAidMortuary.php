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
    public function quota_aid_beneficiaries()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary');
    }
    public function workflow()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\Workflow');
    }
    public function wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState', 'wf_state_current_id', 'id');
    }
    public function tags()
    {
        return $this->belongsToMany('Muserpol\Models\Tag')->withPivot(['date', 'user_id']);
    }
    public function discount_types()
    {
        return $this->belongsToMany('Muserpol\Models\DiscountType')->withPivot(['amount', 'date', 'code', 'note_code', 'note_code_date'])->withTimestamps();
    }
    public function quota_aid_correlative()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidCorrelative');
    }
    public function getBasicInfoCode()
    {
        $code = $this->id . " " . ($this->affiliate->id ?? null) . "\n" . "Trámite Nro: " . $this->code . "\nModalidad: " . $this->procedure_modality->name . "\nSolicitante: " . ($this->quota_aid_beneficiaries()->where('type', 'S')->first()->fullName() ?? null);
        $hash = crypt($code, 100);
        return array('code' => $code, 'hash' => $hash);;
    }
    public function hasLegalGuardian()
    {
        return $this->quota_aid_beneficiaries()->where('type', 'S')->first()->quota_aid_legal_guardians()->count();
    }
}
