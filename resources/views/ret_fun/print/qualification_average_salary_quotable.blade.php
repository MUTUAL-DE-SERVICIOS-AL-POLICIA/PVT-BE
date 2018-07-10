@extends('print_global.print')
@section('content')
<div>
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm">
                <td class="px-15 py text-center ">
                    GRADO
                </td>
                <td class="px-15 py text-center ">
                    PRIMER NOMBRE
                </td>
                <td class="px-15 py text-center">
                    SEGUNDO NOMBRE
                </td>
                <td class="px-15 py text-center">
                    APELLIDO PATERNO
                </td>
                <td class="px-15 py text-center">
                    APELLIDO MATERNO
                </td>
                <td class="px-15 py text-center">
                    C.I.
                </td>
            </tr>
        </thead>
        <tbody>
            <tr class="text-sm">
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->degree->shortened ?? null }}</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->first_name }}</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->second_name }}</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->last_name }}</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->mothers_last_name }}</td>
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->identity_card }} {{ $affiliate->city_identity_card->first_shortened ?? null }}</td>
            </tr>
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