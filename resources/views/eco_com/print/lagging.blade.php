@extends('print_global.print')
@section('content')
<div>
    <div style="min-height:900px;height:900px; max-height:900px;">
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Trámite
        </div>
        @include('eco_com.print.info',['eco_com'=>$eco_com])
        <div class="font-bold uppercase m-b-5 counter">
            Datos del solicitante
        </div>
        @include('eco_com.print.applicant_info', ['applicant'=>$eco_com_beneficiary])
        <div class="font-bold uppercase m-b-5 counter">
            Datos Policiales del Titular
        </div>
        @include('eco_com.print.only_police_info', ['affiliate'=>$affiliate])
        @if ($eco_com->hasLegalGuardian())
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Apoderado Legal
        </div>
        @include('eco_com.print.legal_guardian', ['eco_com_legal_guardian' => $eco_com_legal_guardian])
        @endif
        <div class="text-sm m-t-10">
            <div class="text-left block">
                <span class="capitalize">Señor:</span><br>
                <span class="uppercase">CNL. DESP. EDGAR JOSÉ CORTEZ ALBORNOZ</span><br>
                <span class="uppercase font-bold">DIRECTOR GENERAL EJECUTIVO</span><br>
                <span class="uppercase font-bold">MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</span><br>
                <span class="font-bold capitalize">presente.-</span><br>
            </div>
            <div class="text-right block m-t-10 m-b-10">
                <span class="font-bold uppercase">REF: <span class="underline">
                        SOLICITUD DE PAGO REZAGADO DEL COMPLEMENTO ECONÓMICO DEL
                        {{ $eco_com->eco_com_procedure->semester }} SEMESTRE DE LA GESTIÓN
                        {{ $eco_com->eco_com_procedure->getYear() }}
                    </span></span>
            </div>
            <div class="m-b-5">Distinguido Director:</div>
            <br>
            <div class="m-b-10 text-justify">
                Mediante la presente solicito el pago del Complemento Económico del
                {{ $eco_com->eco_com_procedure->semester }} Semestre de la Gestión
                {{ $eco_com->eco_com_procedure->getYear() }} mediante cheque, debido a que por motivos personales no
                pude apersonarme a agencias del Banco Unión S.A. en las fechas establecidas según Comunicado de pago
                para realizar el cobro correspondiente, para tal efecto adjunto fotocopia de Cédula de Identidad.
                <br>
                <br>
                Me despido de su autoridad con las consideraciones mas distinguidas, agradeciendo de antemano su gentil
                atención.
                <br>
                <br>
                Atentamente,
            </div>
        </div>

        <table class="m-t-50 table-info">
            <tbody>
                <tr>
                    <td class="no-border text-center text-base w-50 align-bottom"
                        style="border-radius: 0.5em 0 0 0!important;">
                        <span class="font-bold">
                            ----------------------------------------------------
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="no-border text-center text-base w-50 align-top">
                        <span class="font-bold">{!! strtoupper($eco_com_beneficiary->fullName()) !!}</span>
                        <br />
                        <span class="font-bold">C.I. {{ $eco_com_beneficiary->ciWithExt() }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('footer')
@endsection