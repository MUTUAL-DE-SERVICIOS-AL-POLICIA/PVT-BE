<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class RetFunProcedure extends Model
{
    public function retirement_funds()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund');
    }

    public function hierarchies()
    {
        return $this->belongsToMany('Muserpol\Models\Hierarchy', 'ret_fun_procedures_hierarchies')
            ->withPivot('apply_limit')
            ->withTimestamps();
    }

    public function scopeCurrent()
    {

        if ($c = $this->where('is_enabled',true)->first())
        {
            return $c;
        }
        return false;
    }

    public static function active_procedure()
    {
        return self::where('start_date', '<=', Carbon::now())
                   ->orderBy('start_date', 'desc')
                   ->first();
    }
}
