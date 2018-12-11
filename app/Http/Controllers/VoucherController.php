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

    public function generateVoucher(Affiliate $affiliate){
        return $affiliate;
    }
}
