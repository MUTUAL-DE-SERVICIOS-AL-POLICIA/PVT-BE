@extends('print_global.print') 
@section('content')
<div>
    @include('ret_fun.print.interval_types', ['ret_fun' => $retirement_fund ])
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        DEVOLUCION DE APORTES EN DISPONIBILIDAD
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr class="text-sm">
                    <td class="w-60 text-left px-10 py-3 uppercase">total aportes en disponibilidad</td>
                    <td class="w-25 text-right uppercase px-5 py-3"> 5458.888.888.451 </td>
                    <td class="w-15  text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">con rendimiento del x% anual</td>
                    <td class="text-right uppercase px-5 py-3"> 9999999.451 </td>
                    <td class="text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">devolucion de aportes en disponibilidad</td>
                    <td class="text-right uppercase px-5 py-3"> 99.99 </td>
                    <td class="text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">retencion para pago de prestamo</td>
                    <td class="text-right uppercase px-5 py-3"> 955459.99 </td>
                    <td class="text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">GARANTES</td>
                    <td class="text-right uppercase px-5 py-3"> 955459.99 </td>
                    <td class="text-center uppercase px-5 py-3"> Bs. </td>
                </tr>
                <tr class="text-lg">
                    <td class="text-left px-10 py-3 uppercase font-bold">total fondo de retiro + devolucion</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> 99.99 </td>
                    <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                </tr>
            </tbody>
        </table>
    </div>
    @include('ret_fun.print.qualification_beneficiaries_fair_share', ['beneficiaries'=>$beneficiaries])
</div>
@endsection