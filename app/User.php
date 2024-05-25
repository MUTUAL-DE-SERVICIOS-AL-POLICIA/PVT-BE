<?php

namespace Muserpol;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Muserpol\Helpers\Util;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function economic_complements()
    {
        return $this->hasMany(Models\EconomicComplement::class);
    }
    public function roles()
    {
        return $this->belongsToMany(Models\Role::class, 'role_user');
    }
    public function city()
    {
        return $this->belongsTo(Models\City::class);
    }
    public function wf_records()
    {
        return $this->hasMany(Models\WorkflowRecord::class);
    }

    public function scopeIdIs($query, $id)
    {
        return $query->where('id', $id);
    }

    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }

    public function getAllRolesToString(){

       $roles_list=[];
       foreach ($this->roles as $role) {
           $roles_list[]=$role->name;
       }
       return implode(",",$roles_list);

    }

    public function getModule(){
        return $this->roles()->first()->module;
    }

    public function retirement_funds()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund');
    }
    public function quota_aid_mortuaries()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuaries\QuotaAidMortuary');
    }
    public function hasRole($rol_id)
    {
        foreach($this->roles as $rol){
            if($rol->id == $rol_id){
                return true;
            }
        }
        return false;
    }
    public function eco_com_review_procedures()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComReviewProcedure');
    }
    public function eco_com_fixed_pensions()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComFixedPension');
    }
}
