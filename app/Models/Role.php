<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\Workflow\WorkflowState;

class Role extends Model
{
    // 
    protected $table = 'roles';
    protected $fillable = [
        'module_id',
        'display_name',
        'action',
        'name'
    ];

    protected $guarded = ['id'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function scopeModuleidIs($query, $id)
    {
        return $query->where('module_id', $id);
    }

    public function users()
    {
    	return $this->belongsToMany('Muserpol\User', 'role_user');
    }
    public function wf_states()
    {
    	return $this->hasMany(WorkflowState::class);
    }

}
