@extends('print_global.print')
@section('content')
<div style="min-height:1000px;">

    <div class="font-bold m-b-5 counter">
        DATOS PERSONALES DEL TITULAR
    </div>
    @include('eco_com.print.only_personal_info', ['affiliate'=>$affiliate])

    <div class="font-bold uppercase m-b-5 counter">
        Datos del Beneficiario
    @if($eco_com->is_paid)
        - PAGO POR ÚNICA VEZ
            @if($beneficiary_one_payment->type === 'V')
            VIUDEDAD
            @else
            ORFANDAD
            @endif
    </div>
        @include('eco_com.print.applicant_info', ['applicant'=>$beneficiary_one_payment])
    @else
    </div>
        @include('eco_com.print.applicant_info', ['applicant'=>$eco_com_beneficiary])
    @endif

    <div class="font-bold uppercase m-b-5 counter">
        Datos del Trámite
    </div>
    @include('eco_com.print.info',['eco_com'=>$eco_com])

    <div class="font-bold uppercase m-b-5 counter">
        Datos Policiales del Titular
    </div>
    @include('eco_com.print.only_police_info', ['affiliate'=>$affiliate])

    <div class="font-bold uppercase m-b-5 counter">
        Cálculo del complemento económico {{ $eco_com_procedure->semester }} semestre {{ $eco_com_procedure->getYear() }}
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
                <td class="w-60 text-left px-10 py-3 uppercase">CATEGORÍA (según años de servicio)</td>
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
    <div class="p-10 border justify rounded"><span class="font-bold">NOTA:</span> {{ $eco_com->comment }}</div>
    <br>
    <div style="border:1px solid #5d6975; border-radius:10px; overflow:hidden; ">
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="height:120px; border-right:1px solid #5d6975;"></td>
                <td style="border-right:1px solid #5d6975;"></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center; border-top:1px solid #5d6975; border-right:1px solid #5d6975;">
                    <strong>ELABORADO POR</strong>
                </td>
                <td style="text-align:center; border-top:1px solid #5d6975; border-right:1px solid #5d6975;">
                    <strong>REVISADO POR</strong>
                </td>
                <td style="text-align:center; border-top:1px solid #5d6975;">
                    <strong>APROBADO POR</strong>
                </td>
            </tr>
            <tr>
                <td style="border-top:1px solid #5d6975; border-right:1px solid #5d6975;">
                    <span>FECHA:</span>
                </td>
                <td style="border-top:1px solid #5d6975; border-right:1px solid #5d6975;">
                    <span>FECHA:</span>
                </td>
                <td style="border-top:1px solid #5d6975;">
                    <span>FECHA:</span>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
@section('other_content')
@include('eco_com.print.ticket')
@endsection