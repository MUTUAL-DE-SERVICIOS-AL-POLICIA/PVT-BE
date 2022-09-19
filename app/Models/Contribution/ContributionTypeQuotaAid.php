<?php

namespace Muserpol\Models\Contribution;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class ContributionTypeQuotaAid extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'shortened', 'operator','description','secuence'];
}
