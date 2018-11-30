<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\DirectContribution;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Helpers\Util;

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

        $title = $direct_contribution->procedure_modality->procedure_type->name. ' -  '. $direct_contribution->procedure_modality->name;

        $code = $contribution_process->code;
        $area = $contribution_process->wf_state->first_shortened;
        $user = $contribution_process->user;
        $date = Util::getDateFormat($contribution_process->date);
        $number = $code;
        $data = [
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,

            'title' => $title,

            'direct_contribution' => $direct_contribution,
            'contribution_process' => $contribution_process,
            'contributions' => $contributions,
            'applicant' => $applicant,
            'affiliate' => $affiliate,
        ];
        $pages[] = \View::make('contribution_process.print.quotation', $data)->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-top', '0mm')
            ->setOption('margin-bottom', '0mm')
            // ->setOption('margin-left', '0mm')
            // ->setOption('margin-right', '0mm')
            // ->setOption('footer-right', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
            // ->setOption('footer-right', 'Pagina [page] de [toPage]')
            ->stream("cotizacion.pdfs");
        return view('contribution_process.print.quotation', $data);
    }
    public function printVoucher($direct_contribution_id, $contribution_process_id)
    {
        $contribution_process = ContributionProcess::find($contribution_process_id);
        $direct_contribution = $contribution_process->direct_contribution;
        $affiliate = $direct_contribution->affiliate;

        $voucher =  $contribution_process->voucher;
        $applicant = $affiliate;
        $description = "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem, eaque.";

        $title = "RECIBO OFICIAL";

        $code = $voucher->code;
        $user = $voucher->user;
        $date = Util::getDateFormat($voucher->payment_date);
        $number = $code;

        $data = [
            'code' => $code,
            'user' => $user,
            'date' => $date,

            'title' => $title,

            'direct_contribution' => $direct_contribution,
            'contribution_process' => $contribution_process,
            'voucher' => $voucher,
            'description' => $description,
            'applicant' => $applicant,
            'affiliate' => $affiliate,
        ];
        $pages[] = \View::make('voucher.print.main', $data)->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-top', '0mm')
            ->setOption('margin-bottom', '0mm')
            ->stream("voucher.pdf");
    }
}
