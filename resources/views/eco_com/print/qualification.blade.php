@extends('print_global.print')
@section('content')
<div style="min-height:1000px;">
    <div class="font-bold uppercase m-b-5 counter">
        Datos del Beneficiario
    </div>
    @include('eco_com.print.applicant_info', ['applicant'=>$eco_com_beneficiary])
    <div class="font-bold uppercase m-b-5 counter">
        Datos del Trámite
    </div>
    @include('eco_com.print.info',['eco_com'=>$eco_com])
    <div class="font-bold uppercase m-b-5 counter">
        Datos Policiales del Titular
    </div>
    @include('eco_com.print.only_police_info', ['affiliate'=>$affiliate])
    <div class="font-bold uppercase m-b-5 counter">
        Calculo del complemento economico {{ $eco_com_procedure->semester }} semestre {{ $eco_com_procedure->getYear() }}
    </div>
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td class="px-15 text-center">
                    Detalle
                </td>
                <td class="px-15 text-center">
                    A favor
                </td>
                <td class="px-15 text-center">
                    Descuento
                </td>
            </tr>
        </thead>
        <tbody class="table-striped text-xs">
            <tr class="text-sm ">
                <td class="w-60 text-left px-10 py-3 uppercase ">TOTAL RENTA O PENSIÓN</td>
                <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($eco_com->total_rent)}} </td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="w-60 text-left px-10 py-3 uppercase">RENTA O PENSION PROMEDIO (según corresponda)</td>
                <td class="w-15 text-right uppercase px-5 py-3">{{ ($eco_com->total_rent_calc == $eco_com->total_rent) ? NULL : Util::formatMoney($eco_com->total_rent_calc) }} </td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="w-60 text-left px-10 py-3 uppercase">HABER BÁSICO (Servicio activo)</td>
                <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($eco_com->salary_reference)}} </td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="w-60 text-left px-10 py-3 uppercase">CATEGORIÍA (según años de servicio)</td>
                <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($eco_com->seniority)}} </td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="w-60 text-left px-10 py-3 uppercase">ANTIGÜEDAD (HABER BÁSICO + CATEGORÍA)</td>
                <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($eco_com->salary_quotable)}} </td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="w-60 text-left px-10 py-3 uppercase">DIFERENCIA (ANTIGÜEDAD - TOTAL RENTA O PENSIÓN)</td>
                <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($eco_com->difference)}} </td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                @if ($eco_com->months_of_payment === null)
                    <td class="w-60 text-left px-10 py-3 uppercase">TOTAL SEMESTRE (DIFERENCIA SE MULTIPLICA POR 6 MESES)</td>
                @else 
                    <td class="w-60 text-left px-10 py-3 uppercase">TOTAL SEMESTRE (DIFERENCIA SE MULTIPLICA POR {{ $eco_com->months_of_payment }} MESES)</td>
                @endif
                <td class="w-15 text-right uppercase px-5 py-3"> 
                    {{ Util::formatMoney($eco_com->getTotalSemester((int) $eco_com->months_of_payment))}} 
                </td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="w-60 text-left px-10 py-3 uppercase">FACTOR DE COMPLEMENTACIÓN</td>
                <td class="w-15 text-right uppercase px-5 py-3"> {{ $eco_com->getComplementaryFactor() }} %</td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            @if ($eco_com->hasDiscountTypes())
            <tr class="text-sm">
                <td class="w-60 text-left px-10 py-3 uppercase">TOTAL COMPLEMENTO ECONÓMICO EN BOLIVIANOS</td>
                <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($eco_com->getOnlyTotalEcoCom() )}} </td>
                <td class="w-15  text-center uppercase px-5 py-3"></td>
            </tr>
            @endif
            @foreach ($eco_com->discount_types as $d)

                <tr class="text-sm">
                    <td class="w-60 text-left px-20 py-3 uppercase"> - {{ $d->name }}</td>
                    <td class="w-15  text-center uppercase px-5 py-3"></td>
                    <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($d->pivot->amount)}} </td>
                </tr> 
            @endforeach
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase font-bold">{{$eco_com->hasDiscountTypes() ? 'TOTAL LIQUIDO A PAGAR EN BOLIVIANOS' : 'TOTAL COMPLEMENTO ECONÓMICO EN BOLIVIANOS'}}</td>
                <td class="text-right uppercase px-5 py-3 font-bold"> {{ Util::formatMoney($eco_com->total) }} </td>
                <td class="text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase"  colspan=3>Son: {{ Util::convertir($eco_com->total) }} BOLIVIANOS</td>
            </tr>
            @if (isset($eco_com->total_repay))
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase font-bold">TOTAL PAGADO</td>
                <td class="text-right uppercase px-5 py-3 font-bold"> {{ Util::formatMoney($eco_com->total - $eco_com->total_repay) }} </td>
                <td class="text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase"  colspan=3>Son: {{ Util::convertir($eco_com->total - $eco_com->total_repay) }} BOLIVIANOS</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase font-bold">REINTEGRO</td>
                <td class="text-right uppercase px-5 py-3 font-bold"> {{ Util::formatMoney($eco_com->total_repay) }} </td>
                <td class="text-center uppercase px-5 py-3"></td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase"  colspan=3>Son: {{ Util::convertir($eco_com->total_repay) }} BOLIVIANOS</td>
            </tr>
            @endif
        </tbody>
    </table>
    <div class="p-10 border justify rounded"><span class="font-bold uppercase">nota:</span> {{ $eco_com->comment }}</div>

    <table class="m-t-25 border table-info">
        <tbody>
            <tr >
                <td class="no-border text-center text-base w-33 align-bottom py-50" style="border-bottom:1px solid #5d6975!important;border-right:1px solid #5d6975!important; border-radius:0 !important">
                </td>
                <td class="no-border  text-center text-base w-33 align-bottom" style="border-bottom:1px solid #5d6975!important;border-right:1px solid #5d6975!important; border-radius:0 !important">
                </td>
                <td class="no-border text-center text-base w-33 align-bottom" style="border-bottom:1px solid #5d6975!important; border-radius:0 !important">
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="no-border text-center text-base py-10 w-33 align-top" style="border-right:1px solid #5d6975!important; border-radius:0 !important" >
                    <span class="font-bold uppercase">Elaborado por</span>
                </td>
                <td class="no-border text-center text-base py-10 w-33 align-top" style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                    <span class="font-bold uppercase">revisador por</span>
                </td>
                <td class="no-border  text-center text-base py-10 w-33 align-top">
                    <span class="font-bold uppercase">V° B°</span>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
@section('other_content')
@include('eco_com.print.ticket')
@endsection