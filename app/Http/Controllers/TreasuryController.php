<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Voucher;
use Muserpol\Helpers\Util;
use DB;
use Log;
use Muserpol\Models\VoucherType;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
class TreasuryController extends Controller
{
    public function selectReport()
    {
        $voucher_types = VoucherType::orderBy('id')->get();
        $data = [
            'voucher_types' => $voucher_types
        ];
        return view('treasury.select_report', $data);
    }
    public function report(Request $request)
    {
        $from = now()->toDateString();
        $to = now()->toDateString();
        if($request->has('from')) {
            $from = Util::verifyBarDate($request->from) ? Util::parseBarDate($request->from) : $request->from;
        }
        if($request->has('to')) {
            $to = Util::verifyBarDate($request->to) ? Util::parseBarDate($request->to) : $request->to;
        }
        if($request->type == 'general') {
            $client = new Client();
            $guzzle_request = $client->request('GET','http://localhost:5000/api/v1/prestamos',[
                'query' => [
                    'from' => $from,
                    'to' => $to
                ]
            ]);
            $response = (string) $guzzle_request->getBody();
            $response = json_decode($response, true);
            $response = $response['recordset'];
        }
        $rows = Voucher::with(['affiliate',
                        'voucher_type',
                        'payable:contributions'])
                        ->whereNotNull('payment_type_id')
                        ->whereBetween('payment_date', [$from, $to])
                        ->orderBy(DB::raw("split_part(vouchers.code, '/', 2)::integer, split_part(vouchers.code, '/', 1)::integer"));
        if ($request->has('type') && $request->type != 'general' ) {
            $rows->where('voucher_type_id', $request->type);
        }
        $rows = $rows->get();
        $number_rows = true;

        foreach ($rows as $r) {
            $r->payment_date = Util::getDateFormat($r->payment_date);
            $r->full_name = $r->affiliate->fullName();
            $r->description = $r->voucher_type->name ?? 'hola' ;
            if ($r->payment_type_id === 1) {
                $r->caja = $r->total;
                $r->banco = "0.00";
            } else {
                $r->caja = "0.00";
                $r->banco = $r->total;
            }
        }
        Log::info($rows->pluck('voucher_type'));
        switch ($request->type) {
            case 'general':
                $title = "Detalle de ingresos de Tesoreria";
                foreach ($response as $key => $value) {
                    $v = new Voucher();
                    $v->payment_date = $value['payment_date'];
                    $v->code = $value['code'];
                    $v->full_name = Util::removeSpaces($value['full_name']);
                    $v->description = $value['description'];
                    $v->caja = $value['caja'];
                    $v->banco = $value['banco'];
                    $rows->push($v);
                }

                Log::info(sizeof($respon9se));
                // $rows = $rows->union(collect($temp));
                $headers = [
                    ['key' => 'payment_date', 'text' => 'Fecha'],
                    ['key' => 'code', 'text' => 'Recibo'],
                    ['key' => 'full_name', 'text' => 'afiliado', 'class' => 'text-left'],
                    ['key' => 'description', 'text' => 'Descripcion', 'class' => 'text-left uppercase'],
                    ['key' => 'caja', 'text' => 'dpto. caja', 'class' => 'text-right'],
                    ['key' => 'banco', 'text' => 'dpto. banco', 'class' => 'text-right'],
                ];

                $total_caja = Util::formatMoney($rows->sum('caja'));
                $total_banco = Util::formatMoney($rows->sum('banco'));
                $footer = [
                    ['key' => 'total', 'text' => 'totales', 'class' => 'pl-70 text-left', 'colspan' => 5],
                    ['key' => 'total_caja', 'text' => ($total_caja ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_banco', 'text' => ($total_banco ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                ];
                break;
            case 1:
                $title = "Aporte Voluntario<br>".VoucherType::find($request->type)->name."<br>De ".$request->from ." a ".$request->to;  ;
                foreach ($rows as $r) {
                    if(optional($r->payable)->contributions) {
                        $months = join(', ', $r->payable->contributions->pluck('month_year')->map(function ($month) {
                            return Util::printMonthYear($month);
                        })->toArray());
                        if(isset($months)) {
                            Log::info($months);
                            $pos = strrpos($months, ', ');
                            if ($pos !== false) {
                                $months = substr_replace($months, ' y ', $pos, strlen(', '));
                            }
                        }
                    }
                    $r->description = $months ?? null;
                    $r->retirement_fund = optional(optional($r->payable)->contributions)->sum('retirement_fund');
                    $r->mortuary_quota = optional(optional($r->payable)->contributions)->sum('mortuary_quota');
                    $r->interest = optional(optional($r->payable)->contributions)->sum('interest');
                    $r->total = optional(optional($r->payable)->contributions)->sum('total');
                }
                $headers = [
                    ['key' => 'payment_date', 'text' => 'Fecha'],
                    ['key' => 'code', 'text' => 'Recibo'],
                    ['key' => 'full_name', 'text' => 'afiliado', 'class' => 'text-left'],
                    ['key' => 'description', 'text' => 'Descripcion', 'class' => 'text-left uppercase'],
                    ['key' => 'retirement_fund', 'text' => 'F.R.', 'class' => 'text-right'],
                    ['key' => 'mortuary_quota', 'text' => 'C.M.', 'class' => 'text-right'],
                    ['key' => 'interest', 'text' => 'Ajuste', 'class' => 'text-right'],
                    ['key' => 'total', 'text' => 'total', 'class' => 'text-right'],
                    ['key' => 'caja', 'text' => 'dpto. caja', 'class' => 'text-right'],
                    ['key' => 'banco', 'text' => 'dpto. banco', 'class' => 'text-right'],
                    ['key' => 'bank_pay_number', 'text' => 'N Boleta Dpto', 'class' => 'text-right'],
                ];

                $total_retirement_fund = Util::formatMoney($rows->sum('retirement_fund'));
                $total_mortuary_quota = Util::formatMoney($rows->sum('mortuary_quota'));
                $total_interest = Util::formatMoney($rows->sum('interest'));
                $total = Util::formatMoney($rows->sum('total'));
                $total_caja = Util::formatMoney($rows->sum('caja'));
                $total_banco = Util::formatMoney($rows->sum('banco'));

                $footer = [
                    ['key' => 'total', 'text' => 'totales', 'class' => 'pl-70 text-left', 'colspan' => 5],
                    ['key' => 'total_retirement_fund', 'text' => ($total_retirement_fund ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_mortuary_quota', 'text' => ($total_mortuary_quota ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_interest', 'text' => ($total_interest ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total', 'text' => ($total ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_caja', 'text' => ($total_caja ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_banco', 'text' => ($total_banco ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => '', 'text' => " ", 'class' => 'text-right px-5', 'colspan' => 1]
                ];
                break;
            case 2:
                $title = "Aporte Voluntario<br>".VoucherType::find($request->type)->name."<br>De ".$request->from ." a ".$request->to;
                foreach ($rows as $r) {
                    if(optional($r->payable)->aid_contributions) {
                        $months = join(', ', $r->payable->contributions->pluck('month_year')->map(function ($month) {
                            return Util::printMonthYear($month);
                        })->toArray());
                        if(isset($months)) {
                            $pos = strrpos($months, ', ');
                            if ($pos !== false) {
                                $months = substr_replace($months, ' y ', $pos, strlen(', '));
                            }
                        }
                    }
                    $r->description = $months ?? null;
                    $r->mortuary_aid = optional(optional($r->payable)->aid_contributions)->sum('mortuary_aid');
                    $r->interest = optional(optional($r->payable)->aid_contributions)->sum('interest');
                    $r->total = optional(optional($r->payable)->aid_contributions)->sum('total');
                }
                $headers = [
                    ['key' => 'payment_date', 'text' => 'Fecha'],
                    ['key' => 'code', 'text' => 'Recibo'],
                    ['key' => 'full_name', 'text' => 'afiliado', 'class' => 'text-left'],
                    ['key' => 'description', 'text' => 'Descripcion', 'class' => 'text-left uppercase'],
                    ['key' => 'mortuary_aid', 'text' => 'A.M.', 'class' => 'text-right'],
                    ['key' => 'interest', 'text' => 'Ajuste', 'class' => 'text-right'],
                    ['key' => 'total', 'text' => 'total', 'class' => 'text-right'],
                    ['key' => 'caja', 'text' => 'dpto. caja', 'class' => 'text-right'],
                    ['key' => 'banco', 'text' => 'dpto. banco', 'class' => 'text-right'],
                    ['key' => 'bank_pay_number', 'text' => 'N Boleta Dpto', 'class' => 'text-right'],
                ];
                $total_mortuary_aid = Util::formatMoney($rows->sum('mortuary_aid'));
                $total_interest = Util::formatMoney($rows->sum('interest'));
                $total = Util::formatMoney($rows->sum('total'));
                $total_caja = Util::formatMoney($rows->sum('caja'));
                $total_banco = Util::formatMoney($rows->sum('banco'));
                $footer = [
                    ['key' => 'total', 'text' => 'totales', 'class' => 'pl-70 text-left', 'colspan' => 5],
                    ['key' => 'total_mortuary_quota', 'text' => ($total_mortuary_aid ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_interest', 'text' => ($total_interest ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total', 'text' => ($total ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_caja', 'text' => ($total_caja ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_banco', 'text' => ($total_banco ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => '', 'text' => " ", 'class' => 'text-right px-5', 'colspan' => 1]
                ];
                break;
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                $title = "Ingresos Depositados en Tesoreria<br>".VoucherType::find($request->type)->name."<br>De ".$request->from ." a ".$request->to;  ;
                $headers = [
                    ['key' => 'payment_date', 'text' => 'Fecha'],
                    ['key' => 'code', 'text' => 'Recibo'],
                    ['key' => 'full_name', 'text' => 'afiliado', 'class' => 'text-left'],
                    ['key' => 'caja', 'text' => 'dpto. caja', 'class' => 'text-right'],
                    ['key' => 'banco', 'text' => 'dpto. banco', 'class' => 'text-right'],
                    ['key' => 'bank_pay_number', 'text' => 'N Boleta Dpto', 'class' => 'text-right'],
                ];
                $total_caja = Util::formatMoney($rows->sum('caja'));
                $total_banco = Util::formatMoney($rows->sum('banco'));
                $footer = [
                    ['key' => 'total', 'text' => 'totales', 'class' => 'pl-70 text-left', 'colspan' => 4],
                    ['key' => 'total_caja', 'text' => ($total_caja ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => 'total_banco', 'text' => ($total_banco ?? "0.00"), 'class' => 'text-right px-5', 'colspan' => 1],
                    ['key' => '', 'text' => ' ', 'class' => 'text-right'],
                ];
                break;
            default:
                # code...
                break;
        }
        $area = 'tesoreria';
        $user = Util::getAuthUser();
        $date = Util::getDateFormat(now());

        $data = [
            'area' => $area,
            'date' => $date,
            'user' => $user,
            'title' => $title,

            'number_rows' => $number_rows,
            'rows' => $rows,
            'headers' => $headers,
            'footer' => $footer,
        ];
        $pages[] = \View::make('treasury.report', $data)->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        $footerHtml = view()->make('print_global.footer')->render();
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '15mm')
            ->setOption('footer-html', $footerHtml)
            ->setOrientation('landscape')
            ->stream("reporte.pdf");
    }
}
