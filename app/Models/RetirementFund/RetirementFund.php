<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function ret_fun_refunds(): HasMany
	{
		return $this->hasMany(RetFunRefund::class);
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

    public function requirementsList()
    {
        try {
            $collateDocuments = app()->call(
                'Muserpol\Gateway\AffiliateController@collateDocument',
                [
                    'affiliateId' => $this->affiliate->id,
                    'procedureModalityId' => $this->procedure_modality_id
                ]
            );
        } catch (\Exception $e) {
            $fullTrace = $e->getTraceAsString();
            $lines = explode("\n", $fullTrace);
            $internalLines = array_filter($lines, function ($line) {
                return strpos($line, '[internal function]') !== false;
            });
            $filteredTrace = implode("\n", $internalLines);
            logger()->error('Error al conectar con los microservicios', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $filteredTrace,
                'controller' => self::class,
                'method' => __FUNCTION__,
                'input' => $data ?? null,
            ]);
            return ['serviceStatus' => 'error'];
        }
        
        $existingIds = [];
        foreach ($collateDocuments['requiredDocuments'] as $group) {
            foreach ($group as $doc) {
                $existingIds[] = $doc['procedureRequirementId'];
            }
        }
        foreach ($collateDocuments['additionallyDocuments'] as $doc) {
            $existingIds[] = $doc['procedureRequirementId'];
        }
        
        //selected documents
        $submitted = RetFunSubmittedDocument::select(
            'ret_fun_submitted_documents.id',
            'procedure_requirements.number',
            'ret_fun_submitted_documents.procedure_requirement_id',
            'ret_fun_submitted_documents.comment',
            'ret_fun_submitted_documents.is_valid',
            'ret_fun_submitted_documents.is_uploaded',
            'procedure_documents.name'
        )
        ->leftJoin('procedure_requirements', 'ret_fun_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
        ->join('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
        ->where('ret_fun_submitted_documents.retirement_fund_id', $this->id)
        ->orderBy('procedure_requirements.number', 'ASC')
        ->get()
        ->keyBy('procedure_requirement_id');

        $applySubmittedData = function (&$doc, $dbDoc) {
            $doc['isUploaded'] = (bool) $dbDoc->is_uploaded;
            $doc['status']     = true;
            $doc['isValid']    = (bool) $dbDoc->is_valid;
            $doc['comment']    = $dbDoc->comment;
            $doc['submittedDocumentId'] = $dbDoc->id;
        };

        // Actualizar requiredDocuments usando referencias
        foreach ($collateDocuments['requiredDocuments'] as &$group) {
            foreach ($group as &$doc) {
                $reqId = $doc['procedureRequirementId'];

                if ($submitted->has($reqId)) {
                    $applySubmittedData($doc, $submitted->get($reqId));
                }
            }
        }
        unset($group, $doc);

        // Actualizar additionallyDocuments usando referencias
        foreach ($collateDocuments['additionallyDocuments'] as &$doc) {
            $reqId = $doc['procedureRequirementId'];

            if ($submitted->has($reqId)) {
                $applySubmittedData($doc, $submitted->get($reqId));
            }
        }
        unset($doc);

        foreach ($submitted as $reqId => $dbDoc) {
            if (!in_array($reqId, $existingIds)) {
                 $newDoc = [
                    "procedureRequirementId" => $dbDoc->procedure_requirement_id,
                    "number"                 => $dbDoc->number,
                    "procedureDocumentId"    => null,
                    "name"                   => $dbDoc->name,
                    "shortened"              => null,
                    "isUploaded"             => (bool) $dbDoc->is_uploaded,
                    "status"                 => true,
                    "isValid"                => $dbDoc->is_valid,
                    "comment"                => $dbDoc->comment,
                ];

                if ($dbDoc->number > 0) {
                    $collateDocuments['requiredDocuments'][$dbDoc->number][] = $newDoc;
                } else {
                    $collateDocuments['additionallyDocuments'][] = $newDoc;
                }
            }
        }
        return $collateDocuments;
    }
}
