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
        @if($eco_com->eco_com_reception_type_id == 2)
            <div class="text-xs">
                <div class="text-left block">
                    <span class="capitalize">Señor:</span><br>
                    <span>CNL. MSc. CAD. LUCIO ENRIQUE RENÉ JIMÉNEZ VARGAS</span><br>
                    <span class="uppercase font-bold">DIRECTOR GENERAL EJECUTIVO</span><br>
                    <span class="uppercase font-bold">MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</span><br>
                    <span class="font-bold capitalize">presente.-</span><br>
                </div>
                <div class="text-right block m-t-10 m-b-10">
                    <span class="font-bold uppercase">REF: <span class="underline">
                            SOLICITUD DE PAGO DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO DEL {{ $eco_com->eco_com_procedure->semester }} SEMESTRE DE LA
                            GESTIÓN {{ $eco_com->eco_com_procedure->getYear() }} NUEVO BENEFICIARIO {{ $eco_com->eco_com_reception_type->name }}
                        </span></span>
                </div>
                <div class="m-b-5">Distinguido Director General Ejecutivo:</div>
                <div class="m-b-10">La presente tiene por objeto solicitar a su autoridad pueda autorizar a la unidad
                    correspondiente 
                    <strong class="uppercase">
                        LA INCLUSIÓN COMO NUEVO BENEFICIARIO PARA EL
                        PAGO DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO DEL {{ $eco_com->eco_com_procedure->semester }}
                        SEMESTRE DE LA GESTIÓN {{ $eco_com->eco_com_procedure->getYear() }}</strong>,
                    en mi calidad de beneficiario titular.
                    <br>Para tal efecto, adjunto los requisitos
                    exigidos de acuerdo al siguiente detalle:</div>
            </div>
        @endif
        @if(sizeof($eco_com_submitted_documents) > 0)
        <div class="font-bold uppercase m-b-5  m-t-5 counter">DOCUMENTOS RECEPCIONADOS</div>
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
                        <td class='text-justify p-5'>{!! $item->procedure_document->name !!}</br>
                    @if(trim($item->comment) != null && trim($item->comment) != '')
                        <span class="text-justify text-xs">* {!! $item->comment !!}</span>
                    @endif
                    </td>
                    @if (true)
                    <td class="text-center">
                        <img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                        {{-- <i class="mdi mdi-checkbox-marked-outline mdi-24px"></i> --}}
                    </td>
                    @else
                    <td class="text-center">
                        <img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAEHSURBVEhL7ZVNCsIwEIXrz1HUYwiuBA8jbkU8h4ILxZ14J0X0DoKI+p5mIAzNJLZ1IfTBh3Uw81EzTbM6v0jbfaamCRqfy/SMwQ0sQIuFSLrgCA6gx0JKJuDpsQGWjJIzkN9fQAeYYcM78EUkJGNDXyIsQTQroBcSLQtJrqAPomEzNtUNiMgsyRAkx5LtQSUSiSXTFJZIUmSlJRLKdiBP8gAjUElCGy/IgJRKTCKUkuknXuDfpWukkCwk4cZzT9ZezecrmSWR6WKz0DQmyXjM8xTWi/NG2JLNgBm+U07AX2Q9JyHZFETD9wmP+phEomVbV0sKx5pH/eD9LR425l3M3XWdv0uWvQDq/6w9IEeDKwAAAABJRU5ErkJggg==">
                        {{-- <i class="mdi mdi-close-box-outline"></i> --}}
                    </td>
                    @endif
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        @endif
    @if(sizeof($eco_com_submitted_documents) > 0)
        @if($eco_com_submitted_documents[0]->number == 0)
        <table class="table-info w-100 m-b-5">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm">
                    <td class="text-center p-5">DOCUMENTOS ADICIONALES</td>
                    <td class="text-center p-5">V°B°</td>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($eco_com_submitted_documents as $i=>$item) @if($item->number == 0)
                <tr>
                    <td class='text-justify p-5'>{!! $item->procedure_document->name !!} </td>
                    @if (true)
                    <td class="text-center">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                    </td>
                    @else
                    <td class="text-center">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAEHSURBVEhL7ZVNCsIwEIXrz1HUYwiuBA8jbkU8h4ILxZ14J0X0DoKI+p5mIAzNJLZ1IfTBh3Uw81EzTbM6v0jbfaamCRqfy/SMwQ0sQIuFSLrgCA6gx0JKJuDpsQGWjJIzkN9fQAeYYcM78EUkJGNDXyIsQTQroBcSLQtJrqAPomEzNtUNiMgsyRAkx5LtQSUSiSXTFJZIUmSlJRLKdiBP8gAjUElCGy/IgJRKTCKUkuknXuDfpWukkCwk4cZzT9ZezecrmSWR6WKz0DQmyXjM8xTWi/NG2JLNgBm+U07AX2Q9JyHZFETD9wmP+phEomVbV0sKx5pH/eD9LR425l3M3XWdv0uWvQDq/6w9IEeDKwAAAABJRU5ErkJggg==">
                    </td>
                    @endif
                </tr>
                @endif @endforeach
            </tbody>
        </table>
        @endif
    @endif
            @if($habitual)
            <br>
            <div class="text-justify text-sm">{{ $text }}</div>
            <br>
            @endif
        <div class="font-bold text-xxs">
            Autorizo a la MUSERPOL acceder a mi información personal (y causante si corresponde) en las bases de datos de Servicio de Registro Cívico - SERECI, 
            Servicio General de Información Personal – SEGIP, Autoridad de Fiscalización y Control de Pensiones y Seguros - APS, 
            Servicio Nacional del Sistema De Reparto - SENASIR, Comando General de la Policía Boliviana y otras Instituciones Públicas y/o 
            Privadas para su verificación o contrastación.
        </div>
        @if($eco_com->eco_com_reception_type_id == 2 || $eco_com->eco_com_reception_type_id == 3)
            <div class="font-bold text-xxs">
            <br>
                En mi calidad de solicitante del beneficio, autorizo de forma expresa a la MUSERPOL para notificarme con cualquier acto administrativo relacionado al 
                Beneficio del Complemento Económico a partir de la presente gestión en adelante al número de WhatsApp y/o correo electronico señalado en el presente formulario.
            </div>
        @endif
        <div class="text-justify text-sm">Sin otro particular me despido de usted muy atentamente.</div>
        @if($eco_com->eco_com_reception_type_id == 2 || $eco_com->eco_com_reception_type_id == 3)
        <table style="margin-top: {{$size_down}}px;" class="m-t-50 table-info">
                <tr>
                    <td class="no-border text-center text-base w-20 align-bottom"
                        style="border-radius: 0.5em 0 0 0!important;">
                        <span class="font-bold">
                            ----------------------------------------------------
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="no-border text-center text-base w-10 align-top">
                        <span class="font-bold">{!! strtoupper($eco_com_beneficiary->fullName()) !!}</span>
                        <br />
                        <span class="font-bold">C.I. {{ $eco_com_beneficiary->ciWithExt() }}</span>
                        <span class="font-bold">TEL. CEL. {{ $eco_com_beneficiary->phone_number }} {{ $eco_com_beneficiary->cell_phone_number }}</span>
                            <br>
                            <span class="font-bold">
                                CORREO ELECTRONICO:
                            </span>
                            <span class="inline-block w-60"></span>
                    </td>
                </tr>
        </table>
        @endif
        @if($habitual)
            <div style="margin-top: {{$size}}px;" class="font-bold text-xxs">
        @else
            <div style="margin-top: 20px;" class="font-bold text-xxs">
        @endif
        </div>
    </div>
</div>
@endsection
@section('footer')
@endsection