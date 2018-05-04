@extends('print_global.print')
@section('content')
    <div>    
        <table class="table-code w-25" align="right">
            <tbody>
                <tr>
                    <td class="p-5 bg-grey-darker">N°:</td>
                    <td class="text-center p-5" colspan="3">{!! $voucher->code !!}</td>
                </tr>
                <tr>
                    <td class="p-5 bg-grey-darker">Bs.:</td>
                    <td class="text-center p-5" colspan="3">{!! $voucher->total !!}.-</td>                </tr>
            </tbody>
        </table>
    </div>
    <br><br><br>
    <div>
        <span><strong>Recibimos de:</strong>
            {{ $beneficiary->first_name }}
            {{ $beneficiary->second_name }}
            {{ $beneficiary->last_name }}
            {{ $beneficiary->mothers_last_name }}
            {{ $beneficiary->surname_husband }}
        </span>
    </div>
    <br><br><br>
    <div>
        <span>Por concepto de pago de los siguientes meses:</span>
            <table class="table-code w-100">
                <tbody>
                    <tr class="font-medium text-white text-sm font-bold bg-grey-darker">
                        <td class="text-center p-5 w-20">MES/AÑO</td>
                        <td class="text-center p-5 w-20">SUELDO</td>
                        <td class="text-center p-5 w-10">F.R.P. (4.77%)</td>
                        <td class="text-center p-5 w-10">CUOTA MORTUORIA (1.09%)</td>
                        <td class="text-center p-5 w-15">AJUSTE</td>
                        <td class="text-center p-5 w-25">SUBTOTAL APORTE</td>
                    </tr>
                        @foreach($contributions as $contribution)
                            <tr>
                                {{-- <td class='text-center p-5'>{!! date("F/Y", strtotime($contribution->month_year)) !!}</td> --}}
                                <td class='text-center p-5'>{!! $util::getStringDate($contribution->month_year) !!}</td>
                                <td class='text-right p-5'>{{ $util::formatMoney($contribution->base_wage) }} </td>
                                <td class='text-right p-5'>{{ $contribution->retirement_fund }} </td>
                                <td class='text-right p-5'>{{ $contribution->mortuary_quota }} </td>
                                <td class='text-right p-5'>{{ $contribution->interest }} </td>    
                                <td class='text-right p-5'>{{ $contribution->total }} </td>
                            </tr>
                        @endforeach
                        <tr>
                        <td colspan="4"></td>
                            <td class="p-5 bg-grey-darker">Total</td>
                            <td class="text-center p-5">{!! $voucher->total !!}</td>
                        </tr>
                </tbody>
            </table>
    </div>
    <table class="table w-100">
        <tbody>
            <tr>
                <td class="p-5">La suma de:</td>
                <td class="p-5" colspan="3"> {!! ucwords(strtolower($total_literal)) !!} Bolivianos</td>
            </tr>
            <tr>
                <td class="p-5">Forma de Pago:</td>
                {{--  <td class="text-center p-5">.......... {!! $item->procedure_requirement->number !!} ..........</td>  --}}
                <td class="text-left py-4 bg-grey-darker">Nro.:</td>
                {{--  <td class="text-center p-5">.......... {!! $item->procedure_requirement->number !!} ..........</td>  --}}
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="text-left py-4 bg-grey-darker">Banco:</td>
                    {{--  <td class="text-center p-5">.......... {!! $item->procedure_requirement->number !!} ..........</td>  --}}
                </tr>
        </tbody>
    </table>
    <p class="text-right"> {{ $date }} </p>
    <table class="m-t-35">
        <tr>
            <th class="no-border text-center" style=" width:60%">
                <p class="font-bold">----------------------------------------------------<br>
                    COBRADO POR<br>
                {!! $name_user_complet !!}<br/>
                </p>
            </th>
        </tr>
    </table>

</div>
@endsection
