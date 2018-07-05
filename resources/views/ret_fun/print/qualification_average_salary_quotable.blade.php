@extends('print_global.print') 
@section('content')
<div>
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='2' class="px-15 text-center">
                    DATOS DEL AFILIADO
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr class="text-sm">
                <td class="w-40 text-left px-10 py-3 uppercase">nombres y apellidos</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $affiliate->fullName() }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">Carnet de identidad</td>
                <td class="text-center uppercase font-bold px-5 py-3">{!! $affiliate->identity_card !!} {{$affiliate->city_identity_card->name ?? ''}}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">fecha de Nacimiento</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->birth_date }}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">Edad</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->calcAge(true, true) }}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">Estado Civil</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->getCivilStatus() }}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">Matricula</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->registration }}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">CUA/NUA</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->nua }}</td>
            </tr>
            @if ($affiliate->date_death)
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">FECHA DE FALLECIMIENTO</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->date_death }}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">CAUSA DE FALLECIMIENTO</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->reason_death }}</td>
            </tr>
            @endif
        </tbody>
    </table>
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td class="px-15 text-center">
                    Nº
                </td>
                <td class="px-15 text-center">
                    PERIODO
                </td>
                <td class="px-15 text-center">
                    HABER BASICO
                </td>
                <td class="px-15 text-center">
                    CATEGORIA
                </td>
                <td class="px-15 text-center">
                    SALARIO COTIZABLE
                </td>
                <td class="px-15 text-center">
                    TOTAL APORTE
                </td>
                <td class="px-15 text-center">
                    APORTE FRPS
                </td>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($contributions as $index => $contribution)
                <tr>
                    <td class="text-right px-10">
                        {{ $index+1 }}
                    </td>
                    <td class="text-right px-5">
                        {{ Util::getDateFormat($contribution->month_year) }}
                    </td>
                    <td class="text-right px-5">
                        {{ Util::formatMoney($contribution->base_wage) }}
                    </td>
                    <td class="text-right px-5">
                        {{ Util::formatMoney($contribution->seniority_bonus) }}
                    </td>
                    <td class="text-right px-5">
                        {{ Util::formatMoney($contribution->seniority_bonus + $contribution->base_wage) }}
                    </td>
                    <td class="text-right px-5">
                        {{ Util::formatMoney($contribution->total) }}
                    </td>
                    <td class="text-right px-5">
                        {{ Util::formatMoney($contribution->retirement_fund) }}
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-right px-5 font-bold">{{ Util::formatMoney($total_base_wage) }}</td>
                    <td class="text-right px-5 font-bold">{{ Util::formatMoney($total_seniority_bonus) }}</td>
                    <td class="text-right px-5 font-bold">{{ Util::formatMoney($sub_total_average_salary_quotable) }}</td>
                    <td class="text-right px-5 font-bold">{{ Util::formatMoney($total_aporte) }}</td>
                    <td class="text-right px-5 font-bold">{{ Util::formatMoney($total_retirement_fund) }}</td>
                </tr>
        </tbody>
    </table>
    <div class="w-50 text-center">
        <p class="uppercase">Total de aporte FRPS: <span class="font-bold">{{Util::formatMoney($total_retirement_fund) }}</span></p>
        <p class="uppercase">Salario Total: <span class="font-bold">{{Util::formatMoney($sub_total_average_salary_quotable) }}</span></p>
        <p class="uppercase">Salario Promedio: <span class="font-bold">{{Util::formatMoney($total_average_salary_quotable) }}</span></p>
    </div>
    <p class="text-left">Es cuanto se certifica los ultimos {{ $number_contributions }} salarios efectivamenete percibidos previos al destino a la disponibilidad de las letras, para fines consiguientes.</p>
</div>
@endsection