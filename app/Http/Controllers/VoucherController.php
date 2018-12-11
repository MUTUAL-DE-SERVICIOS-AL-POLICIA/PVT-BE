<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\Voucher;
use Muserpol\Models\Affiliate;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Muserpol\Helpers\Util;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $last_code = Util::getLastCode(Voucher::class);
        $voucher = new Voucher();
        $voucher->user_id = Auth::user()->id;
        $voucher->affiliate_id = $request->affiliate_id;
        $voucher->voucher_type_id = 1;
        $voucher->total = $request->total;
        $voucher->payment_date = Carbon::now();
        $voucher->code = Util::getNextCode($last_code);
        $voucher->paid_amount = $request->total;
        $voucher->payment_type_id = $request->payment_type_id;
        if($request->payment_type_id == 2) {
            $voucher->bank = $request->bank;
            $voucher->bank_pay_number = $request->bank_pay_number;
        }
        $voucher->save();

        $data = [
            'voucher'   =>  $voucher
        ];

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voucher $voucher)
    {
        //
    }

    public function printVoucher(Affiliate $affiliate, Voucher $voucher) {               

        $title = "RECIBO OFICIAL";
        $code = $voucher->code;
        $user = $voucher->user;
        $date = Util::getDateFormat($voucher->payment_date);
        $number = $code;
        $description = $voucher->type->name;

        $applicant = $affiliate;
        $data = [
            'code' => $code,
            'user' => $user,
            'date' => $date,
            'title' => $title,            
            'voucher' => $voucher,
            'description' => $description,
            'applicant' => $applicant,
//            'affiliate' => $affiliate,
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
