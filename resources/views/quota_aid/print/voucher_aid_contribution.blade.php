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
                    <td class="text-center p-5" colspan="3">{!! $voucher->total !!}.-</td>
            </tbody>
        </table>
    </div>
    <br><br><br>
    <div>
        <span><strong>Recibimos de:</strong>
            {{ $beneficiary->fullName() }}
        </span>
    </div>
    <br>
    <div>
        <span>Por concepto de pago de los siguientes meses:</span>
        <br><br>
        <table class="table-info w-100">
            <tbody>
                <tr class="font-medium text-white text-sm font-bold bg-grey-darker">
                    <td class="text-center p-5 w-10">MES/AÑO</td>
                    <td class="text-center p-5 w-20">COTIZABLE</td>
                    <td class="text-center p-5 w-20">AUXILIO MORTUORIO (2.03%)</td>
                    <td class="text-center p-5 w-10">AJUSTE</td>
                    <td class="text-center p-5 w-20">SUBTOTAL APORTE</td>
                </tr>
                    @foreach($aid_contributions as $aid_contribution)
                        <tr>
                            <td class='text-left px-15'>{{ strtoupper(Util::printMonthYear($aid_contribution->month_year)) }}</td>
                            <td class='text-right px-15'>{{ $util::formatMoney($aid_contribution->quotable) }} </td>
                            <td class='text-right px-15'>{{ $util::formatMoney($aid_contribution->rent *(2.03/100)) }} </td>
                            <td class='text-right px-15'>{{ $util::formatMoney($aid_contribution->interest) }} </td>    
                            <td class='text-right px-15'>{{ $util::formatMoney($aid_contribution->total) }} </td>
                        </tr>
                    @endforeach
                    <tr class="font-bold text-lg bg-grey-lightest">
                        <td colspan="4" class="text-left px-15  rounded-bl">TOTAL</td>
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
                <td class="w-30 px-15">
                    Nro:
                </td>
                <td class="px-15">
                    Banco:
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
    @include('ret_fun.print.signature_footer',['user'=>$user])
@endsection
