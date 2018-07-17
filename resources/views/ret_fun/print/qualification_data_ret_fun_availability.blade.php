@extends('print_global.print')
@section('content')
<div>
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
                <tr>
                    <td class="text-left px-10 py-3 uppercase font-bold">total fondo de retiro + devolucion</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($retirement_fund->total) }} </td>
                    <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                </tr>
            </tbody>
        </table>
    </div>
    @include('ret_fun.print.qualification_beneficiaries_fair_share', ['beneficiaries'=>$beneficiaries, 'type'=>'total'])
</div>
@endsection