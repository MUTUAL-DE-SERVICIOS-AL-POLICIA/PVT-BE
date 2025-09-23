<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;
use Carbon\Carbon;

class EcoComProcedure extends Model
{
    protected $table = 'eco_com_procedures';
    public function economic_complements()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
    public function eco_com_regulations()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComRegulation', 'replica_eco_com_procedure_id');
    }
    public function eco_com_fixed_pensions()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComFixedPension');
    }
    public function getNextProcedure()
    {
        if ($this->semester == 'Primer' ) {
            return EcoComProcedure::where('semester','Segundo')->whereYear('year', $this->getYear())->first();
        }
        return EcoComProcedure::where('semester','Primer')->whereYear('year', $this->getYear()+1)->first();
    }
    public function fullName()
    {
        return  Util::removeSpaces($this->semester.'/SEM/'.Carbon::parse($this->year)->year);
    }
    public function getNameSendBank()
    {
        return "MUSERPOL PAGO COMPLEMENTO ECONOMICO ". ($this->semester == 'Primer' ?  '1ER' : '2DO') ." SEM ". $this->getYear();
    }
    public function getSemesterText()
    {
        return ($this->semester == 'Primer' ?  '1ER' : '2DO') ." SEMESTRE";
    }
    public function getTextName()
    {
        return ($this->semester == 'Primer' ?  '1ER.' : '2DO.') ." SEMESTRE ". $this->getYear();
    }
    public function getYear()
    {
        return Carbon::parse($this->year)->year;
    }
    public function getNormalStartDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getNormalEndDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getLaggingStartDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getLaggingEndDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getAdditionalStartDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getAdditionalEndDateAttribute($value)
    {
        if(!$value){
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function device() {
        return $this->hasOne(AffiliateDevice::class, 'eco_com_procedure_id', 'id', 'affiliate_devices');
    }

    public static function current_procedures()
    {
        $now = Carbon::now()->toDateString();
        return EcoComProcedure::whereDate('normal_start_date', '<=', $now)->whereDate('lagging_end_date', '>=', $now)->get();
    }

    public static function affiliate_available_procedures($affiliate_id)
    {
        $current_procedures = EcoComProcedure::current_procedures()->pluck('id');
        if ($current_procedures->count() > 0) {
            $affiliate_current_procedures = EconomicComplement::where('affiliate_id', $affiliate_id)->whereIn('eco_com_procedure_id', $current_procedures)->pluck('eco_com_procedure_id');
            if ($affiliate_current_procedures->count() < $current_procedures->count()) {
                $affiliate_available_procedures = $current_procedures->diff($affiliate_current_procedures);
                return EcoComProcedure::whereIn('id', $affiliate_available_procedures->values())->orderBy('year')->orderBy('normal_start_date')->get();
            }
        }
        return collect([]);
    }
}
