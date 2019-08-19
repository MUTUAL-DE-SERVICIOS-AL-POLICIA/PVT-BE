@extends('print_global.print')
@section('content')
<div>
    <p class="text-justify">
        La Unidad de Otorgación del Complemento Económico de la Mutual de Servicios al Policía – MUSERPOL, a petición del interesado (a), procedió a la revisión de la documentación e información registrada en la Plataforma
        Virtual de Trámites PVT - MUSERPOL, por cuanto el derecho lo permite.
    </p>
    <p class="font-bold">
        CERTIFICA QUE:
    </p>
    <p class="text-justify">
        El Sr. (a) <strong>{{ $eco_com_beneficiary->fullName() }}</strong> con Cédula de Identidad N°
        <strong>{{ $eco_com_beneficiary->ciWithExt()}}.</strong>, es beneficiario habitual del
        Complemento Económico y registra observación por Reposición de Fondos, a razón del importe de deuda determinado
        por Pagos en Defecto del Complemento Economico a favor de MUSERPOL,
        correspondiente al <span class="italic">{{ $semesters }}</span>, deuda total que asciende a <strong>Bs.
            {{ Util::formatMoney($devolution->total) }} ({{ Util::convertir($devolution->total) }} Bolivianos)</strong>
        y que se encuentra cancelando a la fecha mediante la
        amortización con el
        beneficio del Complemento Económico.
    </p>
    <p class="text-justify">
        En este sentido, se realizó la amortización de deuda por Pagos en Defecto del Complemento Económico, a partir
        del {{ $devolution->eco_com_procedure->getTextName() }}, según compromisos de Devolución, de acuerdo al
        siguiente detalle:
    </p>
    <div class="w-50 mx-auto m-b-20">
        <table class=" w-100 ">
            <tr>
                <td class="border uppercase font-bold p-5">Deuda Total</td>
                <td class="border font-bold text-right p-5">Bs. {{ Util::formatMoney($devolution->total) }}</td>
            </tr>
        </table>
    </div>
    <table class="table-info w-100">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white">
                <td class="px-15 py text-center">
                    Gestión
                </td>
                <td class="px-15 py text-center">
                    Total Complemento <br>
                    Económico (En Bs.)
                </td>
                <td class="px-15 py text-center">
                    Amortización de <br> Deuda (En Bs.)
                </td>
                <td class="px-15 py text-center">
                    Importe Pagado (En Bs.)
                </td>
            </tr>
        </thead>
        <tbody>
        @php($amortizacion=0)
            @foreach ($eco_coms as $eco)
            @php($amortizacion += $eco->discount_types->where('id',6)->first()->pivot->amount)
            <tr>
                <td class="text-left uppercase px-10 py-3">
                    {{ $eco->eco_com_procedure->getTextName()}}
                </td>
                <td class="text-right px-10 py-3">
                    {{ Util::formatMoney($eco->getOnlyTotalEcoCom()) }}
                </td>
                <td class="text-right px-10 py-3">
                    {{ $eco->discount_types->where('id',6)->first()->pivot->amount }}
                </td>
                <td class="text-right px-10 py-3">
                    {{ Util::formatMoney($eco->total) }}
                </td>
            </tr>
            @endforeach
            <tr class="bg-grey-lightest">
                <td class=""></td>
                <td class="uppercase font-bold text-right px-10 py-3">
                    Total Amortización
                </td>
                <td class=" font-bold text-right px-10 py-3">
                    Bs. {{Util::formatMoney($amortizacion) }}
                </td>
                <td class=""></td>
            </tr>
        </tbody>
    </table>
    <div class="w-50 mx-auto m-t-20">
        <table class=" w-100 ">
            <tr>
                <td class="border uppercase font-bold p-5">Deuda Pendiente a la fecha</td>
                <td class="border font-bold  p-5"> Bs. {{ Util::formatMoney($devolution->total-$amortizacion) }}
                </td>
            </tr>
        </table>
    </div>

    <p class="text-justify">
        Por lo cual, actualmente usted tiene una deuda pendiente de <strong>Bs.
            {{ Util::formatMoney(($devolution->total-$amortizacion)) }}
            ({{Util::convertir(($devolution->total-$amortizacion))}} Bolivianos)</strong> por Pagos en Defecto del Complemento
        Economico, que
        deberá ser cancelado conforme compromiso(s) de devolución.
    </p>
    <p class="text-justify">
        Sin embargo, cabe señalar que el monto individual del Complemento Económico es variable,
        determinado semestralmente en base a un Estudio Técnico Financiero y reglamentación aprobado por
        el Directorio de la MUSERPOL, en función a las transferencias determinadas por Ley para el pago del
        Complemento Económico. Asimismo, el Complemento Económico no se encuentra comprendido como
        salario o sueldo, derecho laboral, beneficio social o emergente de aportes a la Seguridad Social de
        Largo Plazo, en razón a su fuente de financiamiento y variabilidad de pago, no siendo parte del Sistema
        de Reparto ni del Sistema Integral de Pensiones, de conformidad a lo establecido en el Decreto
        Supremo Nº 1446 del 19 de diciembre de 2012, modificado mediante Decreto Supremo Nº 3231 del 28
        de junio de 2017.
        <br><br>
        Es cuanto se certifica en honor a la verdad para fines consiguientes.
    </p>
    <p class="text-center">
        {{ $user->city->name ?? 'La Paz' }}, {{ Util::getTextDate() }}
    </p>
    <div>
        <table class="m-t-50">
            <tr>
                <td class="no-border text-center text-base w-50 align-bottom">
                    <span class="font-bold">
                        ----------------------------------------------------
                    </span>
                </td>
                {{--
                <td class="no-border text-center text-base w-50 align-bottom">
                    <span class="font-bold">
                    ----------------------------------------------------
                </span>
                </td> --}}
            </tr>
            <tr>
                {{--
                <td class="no-border text-center text-base w-50">
                    <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                    <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">{!! $user->position !!}</div>
                </td> --}}
                <td class="no-border text-center text-base w-50 align-top">
                    Firma y Sello
                    {{-- <span class="font-bold">{!! strtoupper($eco_com_beneficiary->fullName()) !!}</span>
                    <br />
                    <span class="font-bold">C.I. {{ $eco_com_beneficiary->ciWithExt() }}</span> --}}
                </td>
            </tr>
        </table>
    </div>
    <p class="m-t-40 font-bold text-xxs">NOTA: Este documento no constituye comprobante de pago, ni garantía de futuras
        otorgaciones del beneficio del Complemento Económico.</p>
</div>
@endsection