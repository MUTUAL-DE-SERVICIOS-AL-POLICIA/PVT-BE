@extends('print_global.print') 
@section('content')
<div>
    <div class="font-bold uppercase m-b-5 counter">
        INFORMACIÓN TÉCNICA
    </div>
    @include('ret_fun.print.interval_types', ['ret_fun' => $retirement_fund, 'type'=>'availability'])
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        {{ $availability->display_name }}
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr class="text-sm">
                    <td class="w-60 text-left px-10 py-3 uppercase">total aportes en disponibilidad</td>
                    <td class="w-25 text-right uppercase px-5 py-3"> {{ Util::formatMoney($retirement_fund->subtotal_availability)}} </td>
                    <td class="w-15  text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                <!-- <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">con rendimiento del {{$current_procedure->annual_yield}}% anual</td>
                    <td class="text-right uppercase px-5 py-3"> {{ Util::formatMoney($retirement_fund->total_availability) }} </td>
                    <td class="text-center uppercase px-5 py-3"> Bs. </td>
                </tr> -->
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">{{ $availability->display_name }}</td>
                    <td class="text-right uppercase px-5 py-3"> {{ Util::formatMoney($retirement_fund->total_availability) }} </td>
                    <td class="text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                {{-- <tr>
                    <td class="text-left px-10 py-3 uppercase font-bold">total fondo de retiro + devolucion</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($retirement_fund->total) }} </td>
                    <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                </tr> --}}
            </tbody>
        </table>
    </div>
    @include('ret_fun.print.qualification_beneficiaries_fair_share', ['beneficiaries'=>$beneficiaries, 'type'=>'availability'])
    <!-- <h3 class="uppercase text-center">Fondo de Retiro</h3>
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        TOTALES
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                @foreach ($array_discounts_availability as $item)
                <tr>
                    <td class="text-left px-10 py-3 uppercase">{{$item['name']}}</td>
                    <td class="text-right uppercase px-5 py-3"> {{ Util::formatMoney($item['amount']) }} </td>
                    <td class="text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-left px-10 py-3 uppercase font-bold">total fondo de retiro</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($retirement_fund->total) }} </td>
                    <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                </tr>
            </tbody>
        </table>
    </div>
    @include('ret_fun.print.qualification_beneficiaries_fair_share', ['beneficiaries'=>$beneficiaries, 'type'=>'total']) -->
    {{-- @include('ret_fun.print.signature_footer',['user'=>$user]) --}}
    @include('ret_fun.print.signature_footer_2',['qualification_users'=>$qualification_users])
</div>
@endsection