<?php

namespace Muserpol\Models\Workflow;

use Illuminate\Database\Eloquent\Model;

class WorkflowState extends Model
{
    protected $table = "wf_states";
    public $timestamps = false;
    protected $fillable = ['module_id', 'role_id', 'name','first_shortened', 'sequence_number'];

    public function retirement_funds()
    {
        return $this->hasMany(RetirementFund::class, 'wf_state_current_id', 'id');
    }
    public function tags()
    {
        return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id', 'date'])->withTimestamps();
    }
    public function rol()
    {
        return $this->belongsTo('Muserpol\Models\Role', 'role_id');
    }
}
