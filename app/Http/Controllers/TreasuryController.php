<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Voucher;
use Muserpol\Helpers\Util;

class TreasuryController extends Controller
{
    public function report(Request $request)
    {
        $rows = Voucher::with('affiliate')->with('voucher_type')->whereNotNull('payment_type_id')->where('voucher_type_id', 1)->get();
        foreach ($rows as $r) {
            $r->full_name = $r->affiliate->fullName();
            $r->voucher_type = $r->voucher_type->name;
            if($r->payment_type_id === 1){
                $r->caja = Util::formatMoney($r->total);
                $r->banco = Util::formatMoney("0.00");
            }else{
                $r->caja = Util::formatMoney("0.00");
                $r->banco = Util::formatMoney($r->total);
            }
        }
        $headers = [
            'payment_date' => 'Fecha',
            'code' => 'Recibo',
            'full_name' => 'afiliado',
            'voucher_type' => 'concepto',
            'caja' => 'dpto. caja',
            'banco' => 'dpto. banco',
        ];
        $total_caja = Util::formatMoney($rows->sum('caja'));
        $total_banco = Util::formatMoney($rows->sum('banco'));
        $footer = [
            ['key' => 'total', 'text' => 'totales', 'class' => 'pl-70 text-left', 'colspan' => 4],
            ['key' => 'total_caja', 'text' => $total_caja, 'class' => 'text-right px-5', 'colspan' => 1],
            ['key' => 'total_banco', 'text' => $total_banco, 'class' => 'text-right px-5', 'colspan' => 1]
        ];

        $area = 'tesoreria';
        $user = Util::getAuthUser();
        $date = Util::getDateFormat(now());

        $title = "SOME TITLE";
        $data = [
            'area' => $area,
            'date' => $date,
            'user' => $user,
            'title' => $title,

            'rows' => $rows,
            'headers' => $headers,
            'footer' => $footer,
        ];

        $pages[] = \View::make('trasury.report', $data)->render();

        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
                //    ->setOption('margin-top', '20mm')
                   ->setOption('margin-bottom', '15mm')
                //    ->setOption('margin-left', '25mm')
                //    ->setOption('margin-right', '15mm')
                    //->setOption('footer-right', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
                   ->setOption('footer-right', 'Pagina [page] de [toPage]')
                   ->stream("reporte.pdf");
    }
}
