<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;

class ContributionTypeQuotaAid extends Model
{
    protected $fillable = ['name', 'shortened', 'operator','description','secuence'];
}
