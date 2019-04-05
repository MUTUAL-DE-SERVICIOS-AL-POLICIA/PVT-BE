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
    public function eco_com_process()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComProcess');
    }
    public function getNextProcedure()
    {
        return EcoComProcedure::where('sequence',$this->sequence +1)->first();
    }
    public function fullName()
    {
        return  Util::removeSpaces($this->semester.'/'.Carbon::parse($this->year)->year);
    }
    public function getYear()
    {
        return Carbon::parse($this->year)->year;
    }
}
