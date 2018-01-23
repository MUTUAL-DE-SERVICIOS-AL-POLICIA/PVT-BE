<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;

class DirectContribution extends Model
{
    protected $fillable = [
        'user_id',
        'affiliate_id',
        'type',
        'code',
        'quotable',
        'retirement_fund',
        'mortuary_quota',
        'mortuary_aid',
        'subtotal',
        'ipc',
        'total'
	];
}
