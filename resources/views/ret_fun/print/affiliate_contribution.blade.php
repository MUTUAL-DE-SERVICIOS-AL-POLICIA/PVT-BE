@extends('print_global.print')
@section('content')
    <div>
         @include('print_global.police_info', ['affiliate'=>$beneficiary, 'degree'=>$beneficiary->degree, 'exp'=>($beneficiary->city_identity_card->first_shortened ?? null)]) 
    </div>
    <p>
        <strong>Periodo: </strong>
    </p>
    <table class="table-info w-100">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm ">
                <td class="text-center p-5 w-10">MES/AÑO</td>
                <td class="text-center p-5 w-10">COTIZABLE</td>
                <td class="text-center p-5 w-10">APORTE</td>
                <td class="text-center p-5 w-10">F.R.P. (4.77%)</td>
                <td class="text-center p-5 w-20">CUOTA MORTUORIA (1.09%)</td>
                <td class="text-center p-5 w-10">AJUSTE IPC</td>
                <td class="text-center p-5 w-15">TOTAL APORTE</td>
            </tr>
        </thead>
        <tbody>
        @foreach($contributions as $i=>$item)
            <tr>
                <td class='text-left px-10'>
                    @if($item->type == 'R')
                        R.
                    @endif
                    {!! strtoupper(Util::printMonthYear($item->year."-".$item->month."-01")) !!}
                </td>
                <td class='text-right px-10'>{!! $util::formatMoney($item->sueldo) !!} </td>
                <td class='text-right px-10'>{!! $util::formatMoney($item->fr + $item->cm) !!} </td>
                <td class='text-right px-10'>{!! $util::formatMoney($item->fr) !!} </td>
                <td class='text-right px-10'>{!! $util::formatMoney($item->cm) !!} </td>
                <td class='text-right px-10'>{!! $util::formatMoney($item->interes) ?? '0.00' !!} </td>
                <td class='text-right px-10'>{!! $util::formatMoney($item->subtotal) !!} </td>
            </tr>
        @endforeach
            <tr class="font-bold text-lg bg-grey-lightest">
                {{-- <td colspan="4"></td> --}}
                <td colspan="6" class="text-left px-15 font-bold">TOTAL</td>
                <td class='text-right px-15'><strong>{!! $util::formatMoney($total) !!}</strong></td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="border rounded p-5 px-15">
        <span class="font-bold m-r-10">
            Son:
        </span>
        <span class="italic text-lg">{!! ucwords(strtolower($total_literal)) !!} Bolivianos.</span>
    </div>

    @include('ret_fun.print.signature_footer',['user'=>$user])

</div>
@endsection
