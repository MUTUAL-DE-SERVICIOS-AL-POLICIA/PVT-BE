@extends('print_global.print')
@section('content')
    <div>    
        <table class="table-code w-20" align="right">
            <tbody>
                <tr>
                    <td class="font-medium text-white text-sm font-bold p-5 bg-grey-darker text-right">N°:</td>
                    <td class="text-center p-5" colspan="3">{!! $voucher->code !!}</td>
                </tr>
                <tr>
                    <td class="font-medium text-white text-sm font-bold p-5 bg-grey-darker text-right">Bs.:</td>
                    <td class="text-center p-5" colspan="3">{!! $voucher->total !!}</td>
            </tbody>
        </table>
    </div>
    <br><br><br>
    <div>
        <br>
        <span><strong>Recibimos de:</strong>
            {{ $beneficiary->fullName() }}
        </span>
    </div>
    <br>
    <div>
        <span>Por concepto de pago de los siguientes meses:</span>
        <br><br>
            <table class="table-info w-100">
                <thead>
                    <tr class="font-medium text-white text-sm bg-grey-darker">
                        <td class="text-center p-5 w-10">MES/AÑO</td>
                        <td class="text-center p-5 w-10">SUELDO</td>
                        <td class="text-center p-5 w-15">F.R.P. (4.77%)</td>
                        <td class="text-center p-5 w-20">CUOTA MORTUORIA (1.09%)</td>
                        <td class="text-center p-5 w-10">AJUSTE</td>
                        <td class="text-center p-5 w-15">SUBTOTAL APORTE</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contributions as $contribution)
                        <tr>
                            <td class='text-left px-15'> @if($contribution->type == 'R') R. @endif {{ strtoupper(Util::printMonthYear($contribution->month_year)) }}</td>
                            <td class='text-right px-15'>{{ $util::formatMoney($contribution->base_wage) }} </td>
                            <td class='text-right px-15'>{{ $util::formatMoney($contribution->retirement_fund) }} </td>
                            <td class='text-right px-15'>{{ $util::formatMoney($contribution->mortuary_quota) }} </td>
                            <td class='text-right px-15'>{{ $util::formatMoney($contribution->interest)??'0.00' }} </td>    
                            <td class='text-right px-15'>{{ $util::formatMoney($contribution->total) }} </td>
                        </tr>
                    @endforeach
                    <tr class="font-bold text-lg bg-grey-lightest">
                        <td colspan="5" class="text-left px-15  rounded-bl">TOTAL</td>
                        <td class="text-right px-15">{!! $voucher->total !!}</td>
                    </tr>
                </tbody>
            </table>
    </div>
    <br>
    <div class="border rounded p-5 px-15">
        <span class="font-bold m-r-10">
                La suma de:
            </span>
        <span class="italic text-lg">{!! ucwords(strtolower($total_literal)) !!} Bolivianos.</span>
    </div>
    <p class="font-bold m-r-10">
        Forma de Pago:
    </p>
    <table class="table-info rounded m-5">
        <thead class="bg-grey-darker">
            <tr class="text-white text-base">
                <td class="px-15">
                    Banco:
                </td>
                <td class="w-30 px-15">
                    Nro:
                </td>                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td> {!! $voucher->bank !!} </td>
                <td class='text-left px-15'> {!! $voucher->bank_pay_number !!} </td>                
            </tr>
        </tbody>
    </table>
    @include('ret_fun.print.signature_footer',['user'=>$user])
@endsection
