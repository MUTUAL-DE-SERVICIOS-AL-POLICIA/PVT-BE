<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\DirectContribution;
use Muserpol\Models\Contribution\ContributionProcess;

class ContributionProcessCertificationController extends Controller
{
    public function printQuotation($direct_contribution_id, $contribution_process_id)
    {
        $contribution_process = ContributionProcess::find($contribution_process_id);
        $direct_contribution = $contribution_process->direct_contribution;
        $affiliate = $direct_contribution->affiliate;
        if ($direct_contribution->procedure_modality->procedure_type_id == 6) {
            $contributions = $contribution_process->contributions;
            $applicant = $affiliate;
        }else{
            $contributions = $contribution_process->aid_contributions;
            $applicant = $direct_contribution->procedure_modality_id == 2 ? $affiliate->spouse : $affiliate;
        }
        $data = [
            'contribution_process' => $contribution_process,
            'contributions' => $contributions,
            'applicant' => $applicant,
            'affiliate' => $affiliate,
        ];
        return view('contribution_processes.print.quotation', $data);
        return $data;
    }
}
