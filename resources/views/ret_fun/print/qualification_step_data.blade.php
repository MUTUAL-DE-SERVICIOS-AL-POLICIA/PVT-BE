@extends('print_global.print')
@section('content')
<div>
    @include('ret_fun.print.interval_types', ['ret_fun' => $retirement_fund ])
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        DATOS ECONOMICOS DEL AFILIADO
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr class="text-sm">
                    <td class="w-60 text-left px-10 py-3 uppercase">ultimo sueldo percibido</td>
                    <td class="w-25 text-right uppercase font-bold px-5 py-3"> COMMING SSON </td>
                    <td class="w-15  text-center uppercase font-bold px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">salario promedio cotizable</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($retirement_fund->average_quotable) }} </td>
                    <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">densidad total de cotizaciones</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{$total_quotes}} </td>
                    <td class="text-center uppercase font-bold px-5 py-3"> meses </td>
                </tr>
            </tbody>
        </table>
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        DATOS ECONOMICOS DEL AFILIADO
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                @if (sizeOf($discounts)>0)
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3 uppercase">total fondo de retiro</td>
                        <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($retirement_fund->subtotal_ret_fun) }} </td>
                        <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                    </tr>
                @endif
                @foreach ($discounts as $d)
                    <tr class="text-sm">
                        <td class="w-60 text-left px-10 py-3 uppercase">{{ $d->name }}</td>
                        <td class="w-25 text-right uppercase px-5 py-3"> {{ Util::formatMoney($d->pivot->amount) }} </td>
                        <td class="w-15  text-center uppercase px-5 py-3"> Bs. </td>
                    </tr>
                @endforeach
                <tr class="text-lg">
                    <td class="text-left px-10 py-3 uppercase font-bold">total fondo de retiro</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($retirement_fund->total_ret_fun) }} </td>
                    <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                </tr>
            </tbody>
        </table>
    </div>
    @include('ret_fun.print.qualification_beneficiaries_fair_share', ['beneficiaries'=>$beneficiaries, 'type'=>'normal'])
</div>
@endsection