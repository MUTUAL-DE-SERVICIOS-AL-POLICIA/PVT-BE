<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Helpers\Util;
use Muserpol\Models\AffiliatePoliceRecord;

class AffiliateReportController extends Controller
{
    public function printRecordAffiliate($affiliate_id)
    {
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "HISTORIAL DEL AFILIADO";
        $affiliate = Affiliate::find($affiliate_id);
        $user = auth()->user();
        $area = Util::getRol()->wf_states->first()->first_shortened;
        $date = Util::getTextDate();
        $affiliate_records = $affiliate->affiliate_records_pvt()->with(['user:id,username'])->orderByDesc('created_at')->get();
        $records = $affiliate->records()->with(['user:id,username'])->get();
        $affiliate_activities = $affiliate->activities()->with('user:id,username')->orderByDesc('created_at')->get();
        $affiliate_police_records = AffiliatePoliceRecord::where('affiliate_id', $affiliate->id)->orderByDesc('date')->get();
        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'area' => $area,
            'date' => $date,
            'affiliate' => $affiliate,
            'affiliate_records' => $affiliate_records,
            'records' => $records,
            'affiliate_activities' => $affiliate_activities,
            'affiliate_police_records'=> $affiliate_police_records,
            'user' => $user
        ];
        $pages = [];
        $pages[] = \View::make('ret_fun.print.print_record_affiliate', $data)->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('copies', 1)
            ->setOption('margin-bottom', '23mm')
            ->setPaper('letter')->setOption('encoding', 'utf-8')
            ->setOption('footer-right', 'Pagina [page] de [toPage]')
            ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
            //->setOption('footer-html', $footerHtml)
            ->stream("historial.pdf");
    }
}
