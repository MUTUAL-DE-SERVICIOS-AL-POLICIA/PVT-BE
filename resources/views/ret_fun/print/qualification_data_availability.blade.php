@extends('print_global.print') 
@section('content')
<div>
    @include('ret_fun.print.interval_types', ['ret_fun' => $retirement_fund, 'type'=>'availability'])
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        RECONOCIMIENTO DE APORTES EN DISPONIBILIDAD
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr class="text-sm">
                    <td class="w-60 text-left px-10 py-3 uppercase">total aportes en disponibilidad</td>
                    <td class="w-25 text-right uppercase px-5 py-3"> {{ Util::formatMoney($retirement_fund->subtotal_availability)}} </td>
                    <td class="w-15  text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">con rendimiento del {{$current_procedure->annual_yield}}% anual</td>
                    <td class="text-right uppercase px-5 py-3"> {{ Util::formatMoney($retirement_fund->total_availability) }} </td>
                    <td class="text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">RECONOCIMIENTO DE APORTES EN DISPONIBILIDAD</td>
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
    <table class="py-100 m-t-50">
        <tr>
            <td class="no-border text-center text-base w-100 align-bottom">
                <span class="font-bold">
                    ----------------------------------------------------
                </span>
            </td>
        </tr>
        <tr>
            <td class="no-border text-center text-base w-100">
                <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">{!! $user->position !!}</div>
            </td>
        </tr>
    </table>
</div>
@endsection