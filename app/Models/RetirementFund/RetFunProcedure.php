<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\Affiliate;
use Carbon\Carbon;
class RetFunProcedure extends Model
{
    protected $fillable = [
        'start_date',
        'contributions_limit',
    ];
    
    // RELACIONES

    public function retirement_funds()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund');
    }

    public function hierarchies()
    {
        return $this->belongsToMany('Muserpol\Models\Hierarchy', 'ret_fun_procedures_hierarchies')
            ->withPivot('apply_contributions_limit')
            ->withPivot('average_salary_limit')
            ->withTimestamps();
    }

    public function procedure_modalities()
    {
        return $this->belongsToMany('Muserpol\Models\ProcedureModality', 'ret_fun_procedures_modalities')
            ->withPivot('annual_percentage_yield')
            ->withTimestamps();
    }

    // SCOPES
    public function scopeCurrent()
    {

        if ($c = $this->where('is_enabled',true)->first())
        {
            return $c;
        }
        return false;
    }

    // FUNCIONES

    public static function active_procedure()
    {
        return self::where('start_date', '<=', Carbon::now())
                   ->orderBy('start_date', 'desc')
                   ->first();
    }

    public function getSalaryLimitForAffiliate(Affiliate $affiliate)
    {
        $hierarchyId = $affiliate->degree->hierarchy_id ?? null;

        if (!$hierarchyId) {
            return null;
        }

        return $this->hierarchies()
                    ->where('hierarchy_id', $hierarchyId)
                    ->first()
                    ->pivot
                    ->average_salary_limit ?? null;
    }

    public function getAnnualPercentageYieldForModality($modalityId)
    {
        return $this->procedure_modalities()
                    ->where('procedure_modality_id', $modalityId)
                    ->first()
                    ->pivot
                    ->annual_percentage_yield ?? null;
    }

}
