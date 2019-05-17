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
        <div class="text-xs">
            <div class="text-left block">
                <span class="capitalize">Señor:</span><br>
                <span class="uppercase">CNL. DESP. EDGAR JOSÉ CORTEZ ALBORNOZ</span><br>
                <span class="uppercase font-bold">DIRECTOR GENERAL EJECUTIVO</span><br>
                <span class="uppercase font-bold">MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</span><br>
                <span class="font-bold capitalize">presente.-</span><br>
            </div>
            <div class="text-right block m-t-10 m-b-10">
                <span class="font-bold uppercase">REF: <span class="underline">
                        SOLICITUD PAGO COMPLEMENTO ECONÓMICO {{ $eco_com->eco_com_procedure->semester }} SEMESTRE DE LA
                        GESTIÓN {{ $eco_com->eco_com_procedure->getYear() }} BENEFICIARIO
                        {{ $eco_com->reception_type }}
                    </span></span>
            </div>
            <div class="m-b-5">Distinguido Director:</div>
            <div class="m-b-10">La presente tiene por objeto solicitar a su autoridad pueda instruir por la unidad
                correspondiente
                @if($eco_com->reception_type == 'Habitual')
                hacerme el
                @endif
                <strong class="uppercase">
                    @if($eco_com->reception_type != 'Habitual')
                    LA INCLUSIÓN COMO NUEVO BENEFICIARIO PARA EL
                    @endif
                    PAGO DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO DEL {{ $eco_com->eco_com_procedure->semester }}
                    SEMESTRE DE LA GESTIÓN {{ $eco_com->eco_com_procedure->getYear() }}</strong>,
                en mi calidad de beneficiario {{ $eco_com->reception_type }}.
                <br>Para tal efecto, adjunto los requisitos
                exigidos de acuerdo al siguiente detalle:</div>
        </div>

        <div class="font-bold uppercase m-b-5 counter">DOCUMENTOS RECEPCIONADOS</div>
        <table class="table-info w-100 m-b-5">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm">
                    <td class="text-center p-5">N°</td>
                    <td class="text-center p-5">REQUISITOS</td>
                    <td class="text-center p-5">V°B°</td>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($eco_com_submitted_documents as $item)
                @if($item->number > 0)
                <tr>
                    <td class='text-center p-5'>{!! $item->number !!}</td>
                    <td class='text-justify p-5'>{!! $item->procedure_document->name !!} </td>
                    @if (true)
                    <td class="text-center">
                        <i class="mdi mdi-checkbox-marked-outline mdi-24px"></i>
                    </td>
                    @else
                    <td class="text-center">
                        <i class="mdi mdi-close-box-outline"></i>
                    </td>
                    @endif
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        {{-- @if($submitted_documents[0]->procedure_requirement->number != 1)
        <table class="table-info w-100 m-b-5">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm">
                    <td class="text-center p-5">ADICIONALES</td>
                    <td class="text-center p-5">V°B°</td>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($submitted_documents as $i=>$item) @if($item->procedure_requirement->number == 0)
                <tr>
                    <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
                    @if (true)
                    <td class="text-center">
                        <i class="mdi mdi-checkbox-marked-outline mdi-24px"></i>
                    </td>
                    @else
                    <td class="text-center">
                        <i class="mdi mdi-close-box-outline"></i>
                    </td>
                    @endif
                </tr>
                @endif @endforeach
            </tbody>
        </table>
        @endif --}}
        <div class="text-justify text-sm">Sin otro particular me despido de usted muy atentamente.</div>
        <table class="m-t-50">
            <tr>
                <td class="no-border text-center text-base w-50 align-bottom">
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
        </table>
        <div class="m-t-50 font-bold text-xxxs">
            Los datos insertos en la presente solicitud son de plena responsabilidad del solicitante.
            <br>
            Autorizo el acceso a la información correspondiente a mi persona (y causahabiente si corresponde) en las bases de datos de SERECI, SEGIP y otras Instituciones Públicas y/o Privadas de ser necesario para su revisión y/o verificación.
        </div>
    </div>
</div>
@endsection