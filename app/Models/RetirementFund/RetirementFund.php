<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Muserpol\Helpers\Util;

class RetirementFund extends Model
{
    use SoftDeletes;

    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
   /* public function ret_fun_observations()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunObservation');
    }*/
    public function ret_fun_observations()
    {
        return $this->morphToMany('Muserpol\Models\ObservationType', 'observable')->whereNull('observables.deleted_at')->withPivot(['user_id', 'date', 'message', 'enabled', 'deleted_at'])->withTimestamps();
    }
    public function ret_fun_observations_delete()
    {
        return $this->morphToMany('Muserpol\Models\ObservationType', 'observable')->whereNotNull('observables.deleted_at')->withPivot(['user_id', 'date', 'message', 'enabled', 'deleted_at'])->withTimestamps();
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
        return $this->belongsToMany('Muserpol\Models\DiscountType')->withPivot(['amount','date','code','note_code', 'note_code_date'])->withTimestamps();
    }    
    public function ret_fun_state(){
        return $this->belongsTo('Muserpol\Models\RetirementFund\RetFunState','ret_fun_state_id');
    }
    public function ret_fun_records()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunRecord');
    }
    public function tags()
    {
        return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id','date'])->withTimestamps();
    }
    public function contribution_types()
    {
        return $this->belongsToMany('Muserpol\Models\Contribution\ContributionType')->withPivot(['message'])->withTimestamps();
    }
    public function ret_fun_correlative()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetFunCorrelative')->orderBy('id', 'desc');
    }
    public function wf_records()
    {
        return $this->morphMany('Muserpol\Models\Workflow\WorkflowRecord', 'recordable')->orderBy('id', 'desc');
    }
    public function getBasicInfoCode()
    {
        $code = $this->id." ".($this->affiliate->id ?? null) ."\n". "Trámite Nro: ".$this->code."\nModalidad: ".$this->procedure_modality->name."\nSolicitante: ".($this->ret_fun_beneficiaries()->where('type', 'S')->first()->fullName() ?? null);
        $hash = crypt($code, 100);
        return array('code' => $code, 'hash'=>$hash);
        ;
    }
    public function wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState', 'wf_state_current_id', 'id');
    }
    public function workflow()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\Workflow');
    }
    public function procedure_records()
    {
        return $this->morphMany('Muserpol\Models\ProcedureRecord', 'recordable');
    }
    public function submitted_documents()
    {
        return $this->hasMany(RetFunSubmittedDocument::class);
    }
    public function getReceptionSummary(){
        
        $beneficiary = RetFunBeneficiary::where('type','S')->where('retirement_fund_id',$this->id)->first();
        $data = [
            0   =>  $this->affiliate->fullName(),
            1   =>  Util::fullName($beneficiary),
        ];
        return $data;
    }

    public function getFileSummary(){
        $folders = Affiliate::where('affiliate_id',$this->affiliate_id)->get();
        if($folders->count() > 0)
        {
            $result = "";
            foreach($folders as $folder){
                $restul.= $folder->procedure_modality->name."<br>";
            }
        }
        {
            return "El afiliado no tiene documentos referidos";
        }
    }
    public function hasLegalGuardian()
    {
        $beneficiary = $this->ret_fun_beneficiaries()->where('type', 'S')->first();
        if (!$beneficiary) {
            return 0;
        }
        return $beneficiary->legal_guardian()->count();
    }

    public function getCorrelative($area_id)
    {
        return RetFunCorrelative::where('retirement_fund_id', $this->id)->where('wf_state_id', $area_id)->first();
    }
    public function info_loans()
    {
        return $this->hasMany('Muserpol\Models\InfoLoan');
    }
    /*
    Función que devuelve si el trámite de fondo pertenece a reincorporación
    */
    public function isReinstatement()
    {
        $ret_fun_all = RetirementFund::where('affiliate_id', $this->affiliate_id)
            ->where('code', 'NOT LIKE', '%A')
            ->orderBy('reception_date')
            ->orderBy('id', 'ASC')
            ->pluck('id')->all();
        $index = array_search($this->id, $ret_fun_all);
        return $index == 1;
    }
}
