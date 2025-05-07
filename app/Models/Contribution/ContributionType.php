<?php

namespace Muserpol\Models\Contribution;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class ContributionType extends Model
{
    use SoftDeletes;
    public function contributions()
    {
        return $this->hasMany('Muserpol\Models\Contribution\Contribution');
    }
    protected $fillable = ['name', 'shortened', 'operator', 'description', 'sequence'];
}
