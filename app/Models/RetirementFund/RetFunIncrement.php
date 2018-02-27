<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\RoleUser;

class RetFunIncrement extends Model
{       
       
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
    public function role()
    {
    	return $this->belongsTo(\Muserpol\Models\Role::class);
    }
    public function retirement_fund()
    {
    	return $this->belongsTo(RetirementFund::class);
    }

    public static function getIncrement($role_id, $retirement_fund_id){        

        $year =  date('Y');
        $inc = RetFunIncrement::where('role_id',$role_id)
                ->where('retirement_fund_id',$retirement_fund_id)
                ->whereYear('created_at','=',$year)
                ->orderBy('number','DESC')->orderBy('id','DESC')->first();                
        
        $role_user = RoleUser::where('role_id',$role_id)->first();
        if(!isset($inc->id)){
            $inc2 = RetFunIncrement::where('role_id',$role_id)                
                ->whereYear('created_at','=',$year)
                ->orderBy('number','DESC')->orderBy('id','DESC')->first();
            $increment = new RetFunIncrement();
            $increment->role_id = $role_id;
            $increment->retirement_fund_id = $retirement_fund_id;
            if(!isset($inc2->id))
                $increment->number = 1;
            else 
                $increment->number = $inc2->number+1;
            $increment->save();
            $increment = $increment->number;
        }                        
        else
            $increment = $inc->number;
        return $increment."/".$year;

        
    }
}
