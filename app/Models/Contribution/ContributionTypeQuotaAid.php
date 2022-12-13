<?php

namespace Muserpol\Models\Contribution;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class ContributionTypeQuotaAid extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'shortened', 'operator','description','secuence'];
    public function contributions()
    {
        return $this->hasMany('Muserpol\Models\Contribution\Contribution');
    }
    public function contribution_passives()
    {
        return $this->hasMany('Muserpol\Models\Contribution\ContributionPassive');
    }
}
