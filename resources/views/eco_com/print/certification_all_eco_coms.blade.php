@extends('print_global.print')
@section('content')
<div>
    <p class="text-justify">
        La Unidad de Otorgación del Complemento Económico de la Mutual de Servicios al Policía – MUSERPOL, a petición
        del interesado (a), procedió a la revisión de la documentación e información registrada en la Plataforma
        Virtual de Trámites - MUSERPOL.
    </p>
    <p class="font-bold">
        CERTIFICA QUE:
    </p>
    <p class="text-justify">
        El Sr. (a) {{ $eco_com_beneficiary->fullName() }}. con Cédula de Identidad N°
        {{ $eco_com_beneficiary->identity_card}} se benefició con el pago
        del Complemento Económico como {{ $type }}, de acuerdo al siguiente detalle:
    </p>

    <div class="font-bold uppercase m-b-5 counter">
        Datos Personales del Titular
    </div>
    @include('eco_com.print.applicant_info', ['applicant'=>$affiliate])
    <div class="font-bold uppercase m-b-5 counter">
        Datos Policiales del Titular
    </div>
    @include('eco_com.print.only_police_info', ['affiliate'=>$affiliate])
    @if ($type != 'Titular')
    <div class="font-bold uppercase m-b-5 counter">
        Datos del Beneficiario
    </div>
    @include('eco_com.print.applicant_info', ['applicant'=>$eco_com_beneficiary])
    @endif
    <div class="font-bold uppercase m-b-5 m-t-10 counter">
        Pagos Realizados como {{$type_modality}}
    </div>
    <table class="table-info w-100">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white">
                <td class="px-15 py text-center " colspan="2">
                    PAGO DEL COMPLEMENTO ECONÓMICO
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($eco_coms as $eco)
            <tr >
                <td class="text-left uppercase px-10 py-5">
                    {{ $eco->eco_com_procedure->getTextName()}}
                </td>
                <td class="text-left px-10 py-5">
                <span class="font-bold">Bs. {{ Util::formatMoney($eco->getOnlyTotalEcoCom())}}</span> (<span class="italic lowercase">{{Util::convertir($eco->getOnlyTotalEcoCom()) }}</span> Bolivianos)
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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