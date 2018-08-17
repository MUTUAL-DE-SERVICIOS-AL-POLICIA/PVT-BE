<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;

class ContributionType extends Model
{
    //
    public function contributions()
    {
        return $this->hasMany('Muserpol\Models\Contribution\Contribution');
    }
    protected $fillable = ['name', 'shortened', 'operator','description'];
}
