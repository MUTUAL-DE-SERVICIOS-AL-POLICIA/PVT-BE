<?php

namespace Muserpol;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    //
    protected $table = 'role_permissions';
    public function role()
    {
        return $this->belongsTo('Muserpol\Models\Role');
    } 
    public function permission()
    {
        return $this->belongsTo('Muserpol\Permission');
    }
}
