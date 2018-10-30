<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;

class ContributionProcess extends Model
{
    protected $guarded = [];
    public function contributions()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\Contribution', 'quotable')->withTimestamps();
    }
    public function aid_contributions()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\AidContribution', 'quotable')->withTimestamps();
    }
}
