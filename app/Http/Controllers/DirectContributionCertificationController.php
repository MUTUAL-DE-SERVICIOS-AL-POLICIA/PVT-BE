<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\DirectContribution;

class DirectContributionCertificationController extends Controller
{
    public function printCommitmentLetter(DirectContribution $direct_contribution)
    {
        $affiliate = $direct_contribution->affiliate;
        $user = $direct_contribution->user;
        // if ($direct_contribution->procedure_modality-> ) {
        //     # code...
        // }
    }
}
