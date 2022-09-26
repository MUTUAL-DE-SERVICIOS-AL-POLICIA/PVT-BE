<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\ContributionPassive;
use Auth;

class ContributionPassiveController extends Controller
{
    public static function store($data){
        return ContributionPassive::create($data);
    }

    public static function update($data,  $contribution_id){
        $contribution_passive = ContributionPassive::findOrFail($contribution_id);
        $contribution_passive->where('id', $contribution_id)->update($data);
    }
}
