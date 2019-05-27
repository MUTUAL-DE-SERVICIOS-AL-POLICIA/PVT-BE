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
    public function getNextProcedure()
    {
        if ($this->semester == 'Primer' ) {
            return EcoComProcedure::where('semester','Segundo')->whereYear('year', $this->getYear())->first();
        }
        return EcoComProcedure::where('semester','Primer')->whereYear('year', $this->getYear()+1)->first();
    }
    public function fullName()
    {
        return  Util::removeSpaces($this->semester.'/'.Carbon::parse($this->year)->year);
    }
    public function getNameSendBank()
    {
        return "MUSERPOL PAGO COMPLEMENTO ECONOMICO ". ($this->semester == 'Primer' ?  '1ER' : '2DO') ." SEM ". $this->getYear();
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
}
