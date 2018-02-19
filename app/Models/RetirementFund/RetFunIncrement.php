<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;

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
//    public function getCite(User $user, Role $role, ProcedureType $procedure_type){
//        $increments = Increment::where('user_id',$user->id)->where('role_id',$role_id)->where('procedure_type_id',$proce)->first();
//    }
    public static function getCite($user_id, $role_id, $retirement_fund_id){        
        $year =  date('Y');
        $inc = RetFunIncrement::where('user_id',$user_id)
                ->where('role_id',$role_id)
                ->where('retirement_fund_id',$retirement_fund_id)
                ->whereYear('created_at','=',$year)
                ->orderBy('number','DESC')->orderBy('id','DESC')->first();                
        if(!isset($inc->id)){
            $inc2 = RetFunIncrement::where('user_id',$user_id)
                ->where('role_id',$role_id)
                //->where('retirement_fund_id',$retirement_fund_id)
                ->whereYear('created_at','=',$year)
                ->orderBy('number','DESC')->orderBy('id','DESC')->first();
            $increment = new RetFunIncrement();
            $increment->user_id = $user_id;
            $increment->role_id = $role_id;
            $increment->retirement_fund_id = $retirement_fund_id;
            if(!isset($inc2->id))
                $increment->number = 1;
            else 
                $increment->number = $inc2->number+1;
            $increment->save();
            $cite = $increment->role->cite." - ".$increment->number;
        }                        
        else
            $cite = $inc->role->cite." - ".$inc->number;
        return $cite;
    }
//    public static function getCite($user_id, $role_id, $retirement_fund_id){
//        $increment = RetFunIncrement::where('user_id',$user_id)->where('role_id',$role_id)->where('retirement_fund_id',$retirement_fund_id)->orderBy('number','DESC')->orderBy('id','DESC')->first();
//        $cite = $increment->role->cite." - ".$increment->number;
//        return $cite;        
//    }
}
