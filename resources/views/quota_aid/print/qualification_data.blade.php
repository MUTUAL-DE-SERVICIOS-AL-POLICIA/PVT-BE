@extends('print_global.print') 
@section('content')
<div>
    <div class="font-bold uppercase m-b-5 counter">
        INFORMACIÓN TÉCNICA
    </div>
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='5' class="px-15 text-center">
                        APORTES Y PERIODOS CONSIDERADOS
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr>
                    <td class="w-50"></td>
                    <td class="w-25 text-center font-bold px-10 py-3 uppercase" colspan="1">inicio</td>
                    <td class="w-25 text-center font-bold px-10 py-3 uppercase" colspan="1">fin</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">PERIODO DE APORTES CONSIDERADOS PARA EL CÁLCULO DEL BENEFICIO</td>
                    <td colspan="2">
                    <table class="no-border" style="border:none">
                            @foreach ($dates as $d)
                            <tr class="no-border" style="border:none">
                                <td class="text-center uppercase font-bold px-5 py-3" style="border:none"> {{ Util::formatMonthYear($d->start) ?? 'error' }} </td>
                                <td class="text-center uppercase font-bold px-5 py-3" style="border:none"> {{ Util::formatMonthYear($d->end) ?? 'error' }} </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @if($quota_aid->procedure_modality->id !=14)
    <div class="block">
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td class="px-15 text-center">Tipo de contribución</td>
                <td class="px-15 text-center">Años</td>
                <td class="px-15 text-center">Meses</td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">{{ $contributions['contribution_types'] }}</td><!--aqui-->
                <td class="text-center px-10 py-3 uppercase">{{ $contributions['years'] }}</td>
                <td class="text-center px-10 py-3 uppercase">{{ $contributions['months'] }}</td>
            </tr>
            <tr>
                <td class="text-left px-10 py-3 uppercase font-bold">Total de cotizaciones para Calificación</td>
                <td class="text-center"><strong>{{ $contributions['years'] }}</strong></td>
                <td class="text-center"><strong>{{ $contributions['months'] }}</strong></td>
            </tr>
            <tr>
                <td colspan='2'class="text-left px-10 py-3 uppercase font-bold">Densidad total de cotizaciones</td>
                <td class="text-center py-2"><strong>{{ $quota_aid->number_qualified_contributions }}</strong></td>
            </tr>
        </tbody>
    </table>
    </div>
    @endif
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        DATOS ECONOMICOS
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                {{-- <tr class="text-sm">
                    <td class="w-60 text-left px-10 py-3 uppercase">Grado</td>
                    <td class="w-25 text-right uppercase font-bold px-5 py-3"> {{ $affiliate->degree->shortened }} </td>
                </tr>
                <tr class="text-sm">
                    <td class="w-60 text-left px-10 py-3 uppercase">Fecha de Fallecimiento</td>
                    <td class="w-25 text-right uppercase font-bold px-5 py-3"> {{ $affiliate->getDateDeath() }} </td>
                </tr>
                <tr class="text-sm">
                    <td class="w-60 text-left px-10 py-3 uppercase">Causa de Fallecimiento</td>
                    <td class="w-25 text-right uppercase font-bold px-5 py-3"> {{ $affiliate->reason_death }} </td>
                </tr> --}}
                @if ($discount != null)
                <tr class="text-lg">
                    <td class="text-left px-10 py-3 uppercase">SUBTOTAL {{$quota_aid->procedure_modality->procedure_type->second_name}}</td>
                    <td class="text-right uppercase px-5 py-3"> {{ Util::formatMoney($quota_aid->subtotal) }}  Bs.</td>
                </tr>
                @if ($discount->id == 9)
                <tr class="text-md">
                    <td class="text-left px-10 py-3 uppercase"> - {{$discount->shortened}}</td>
                    <td class="text-right uppercase px-5 py-3">- {{ Util::formatMoney($discount->pivot->amount) }}  Bs.</td>
                </tr>
                @endif
                @endif
                <tr class="text-xl font-bold">
                    <td class="text-left px-10 py-3 uppercase">TOTAL {{$quota_aid->procedure_modality->procedure_type->second_name}}</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($quota_aid->total) }}  Bs.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='4' class="px-15 text-center">
                        CALCULO DE CUOTAS PARTE PARA DERECHOHABIENTES
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr>
                    <td class="w-40 text-center font-bold px-10 py-3 uppercase">
                        nombre del beneficiario
                        <!-- nombre del {{($quota_aid->procedure_modality->id == 1 || $quota_aid->procedure_modality->id == 4) ? 'derechohabiente' : 'titular' }} -->
                    </td>
                    <td class="w-20 text-center font-bold px-10 py-3 uppercase">% de asignacion</td>
                    <td class="w-20 text-center font-bold px-10 py-3 uppercase">monto</td>
                    <td class="w-20 text-center font-bold px-10 py-3 uppercase">parentesco</td>
                </tr>
                @foreach ($beneficiaries as $beneficiary)
                <tr class="text-sm">
                    <td class="text-left uppercase px-5 py-3"> {{ $beneficiary->fullName() }} </td>
                    <td class="text-center uppercase px-5 py-3">
                        <div class="w-70 text-right">{!! $beneficiary->percentage !!}</div>
                    </td>
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {!! Util::formatMoney($beneficiary->paid_amount) !!}
                    </td>
                    <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? 'error' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('ret_fun.print.signature_footer',['user'=>$user]) 
</div>
@endsection