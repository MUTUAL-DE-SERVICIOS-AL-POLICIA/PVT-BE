@extends('print_global.print')
@section('content')
<div>
    <div class="font-bold uppercase m-b-5 counter">
        INFORMACIÓN TÉCNICA
    </div>
    @include('ret_fun.print.interval_types', ['ret_fun' => $retirement_fund, 'type'=>'ret_fun' ])
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
                @if (in_array($retirement_fund->procedure_modality->procedure_type_id, [1, 21]))
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3 uppercase">TOTAL APORTES</td>
                        <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($sum_contributions) }} </td>
                        <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                    </tr>
                    @if($yield>0)
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3 uppercase">{{$percentage_yield}}% DE RENDIMIENTO</td>
                        <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($yield) }} </td>
                        <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                    </tr>
                    @endif
                @else
                    <tr class="text-sm">
                        <td class="w-60 text-left px-10 py-3 uppercase">ultimo sueldo percibido</td>
                        <td class="w-25 text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($affiliate->getLastBaseWage()) ?? '-' }} </td>
                        <td class="w-15  text-center uppercase font-bold px-5 py-3"> Bs. </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3">SALARIO PROMEDIO COTIZABLE</td>
                        <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($retirement_fund->used_limit_average) }} </td>
                        <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3 uppercase">densidad total de cotizaciones</td>
                        <td class="text-right uppercase font-bold px-5 py-3"> {{$total_quotes}} </td>
                        <td class="text-center uppercase font-bold px-5 py-3"> meses </td>
                    </tr>
                @endif
    </tbody>
</table>
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        DATOS ECONÓMICOS DEL AFILIADO
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                @if (sizeOf($discounts)>0)
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3 uppercase">
                            @if ($retirement_fund->procedure_modality->procedure_type_id == 1)
                                total pago global por {{ $retirement_fund->procedure_modality->name }}
                            @elseif ($retirement_fund->procedure_modality->procedure_type_id == 21)
                                total devolución de aportes por {{ $retirement_fund->procedure_modality->name }}
                            @else
                                total fondo de retiro
                            @endif
                        </td>
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
                @foreach ($array_discounts_combi as $item)
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3 uppercase">{{$item['name']}}</td>
                        <td class="text-right uppercase px-5 py-3"> {{ Util::formatMoney($item['amount']) }} </td>
                        <td class="text-center uppercase px-5 py-3"> Bs. </td>
                    </tr>
                @endforeach
                <tr class="text-lg">
                    <td class="text-left px-10 py-3 uppercase font-bold">
                        @if ($retirement_fund->procedure_modality->procedure_type_id == 1)
                            total pago global por {{ $retirement_fund->procedure_modality->name }}
                        @elseif ($retirement_fund->procedure_modality->procedure_type_id == 21)
                            total devolución de aportes por {{ $retirement_fund->procedure_modality->name }}
                        @else
                            total fondo de retiro
                        @endif
                    </td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($retirement_fund->total_ret_fun) }} </td>
                    <td class="text-center uppercase font-bold px-5 py-3"> Bs. </td>
                </tr>
            </tbody>
        </table>
    </div>
    @include('ret_fun.print.qualification_beneficiaries_fair_share', ['beneficiaries'=>$beneficiaries, 'type'=>'normal'])
    {{-- @include('ret_fun.print.signature_footer',['user'=>$user]) --}}
    @include('ret_fun.print.signature_footer_2',['qualification_users'=>$qualification_users])
</div>
@endsection