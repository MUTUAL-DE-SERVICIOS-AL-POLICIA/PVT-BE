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
    public function submitted_documents()
	{
		return $this->hasMany(QuotaAidSubmittedDocument::class);
    }
    // public function quota_aid_observation()
    // {
    //     return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidObservation');
    // }
    public function quota_aid_observations()
    {
        return $this->morphToMany('Muserpol\Models\ObservationType', 'observable')->whereNull('observables.deleted_at')->withPivot(['user_id', 'date', 'message', 'enabled', 'deleted_at'])->withTimestamps();
    }
    public function quota_aid_observations_delete()
    {
        return $this->morphToMany('Muserpol\Models\ObservationType', 'observable')->whereNotNull('observables.deleted_at')->withPivot(['user_id', 'date', 'message', 'enabled', 'deleted_at'])->withTimestamps();
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
        return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id','date'])->withTimestamps();
    }
    public function discount_types()
    {
        return $this->belongsToMany('Muserpol\Models\DiscountType')->withPivot(['amount', 'date', 'code', 'note_code', 'note_code_date'])->wherePivot('deleted_at',null)->withTimestamps();
    }
    public function quota_aid_correlative()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidCorrelative');
    }
    public function wf_records()
    {
        return $this->morphMany('Muserpol\Models\Workflow\WorkflowRecord', 'recordable')->orderBy('id', 'desc');
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
    public function hasCorrelative($id = null)
    {
        return !! $this->quota_aid_correlative()->where('wf_state_id', $id)->first();
    }
    public function isQuota()
    {
        return $this->procedure_modality->procedure_type_id == 3;
    }
    public function isAid()
    {
        return $this->procedure_modality->procedure_type_id == 4;
    }
    public function getCorrelative($area_id)
    {
        return QuotaAidCorrelative::where('quota_aid_mortuary_id', $this->id)->where('wf_state_id', $area_id)->first();
    }
    public function getApplicant()
    {
        return $this->quota_aid_beneficiaries()->where('type', 'like','S')->first();
    }
    public function getDeceased()
    {
        if ($this->isQuota()) {
            if ($this->getTypeMortuary() == 'Titular' || $this->getTypeMortuary() == null) {
                return $this->affiliate;
            } else {
                return $this->affiliate->spouse->first();
            }
        }
        if ($this->isAid()) {
            return $this->procedure_modality->id == 13 ? $this->affiliate : $this->affiliate->spouse->first();
        }
        return null;
    }
    public function getTypeMortuary()
    {
        if ($this->quota_aid_procedure == null) {
            return null;
        } else {
            return $this->quota_aid_procedure->type_mortuary;
        }
    }
    public function procedure_records()
    {
        return $this->morphMany('Muserpol\Models\ProcedureRecord', 'recordable');
    }
    public function contribution_types()
    {
        return $this->belongsToMany('Muserpol\Models\Contribution\ContributionTypeQuotaAid')->withPivot(['message'])->withTimestamps();
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
        $submitted = QuotaAidSubmittedDocument::select(
            'quota_aid_submitted_documents.id',
            'procedure_requirements.number',
            'quota_aid_submitted_documents.procedure_requirement_id',
            'quota_aid_submitted_documents.comment',
            'quota_aid_submitted_documents.is_valid',
            'quota_aid_submitted_documents.is_uploaded',
            'procedure_documents.name'
        )
        ->leftJoin('procedure_requirements', 'quota_aid_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
        ->join('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
        ->where('quota_aid_submitted_documents.quota_aid_mortuary_id', $this->id)
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
