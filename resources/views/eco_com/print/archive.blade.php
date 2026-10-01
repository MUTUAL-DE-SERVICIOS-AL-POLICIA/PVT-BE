@extends('eco_com.print.print')
@section('content')
<div>
    <div style="min-height:900px;height:900px; max-height:900px;">
                    <div class="text-xs">
                <div class="text-left block text-sm">
                    <span class="text-justify"><br>El suscrito Profesional de Archivo y Gestión Documental de Beneficios Económicos</span><br>   
                    <span class="uppercase font-bold">CERTIFICA QUE:</span><br>
                    <span class="text-justify">Los documentos presentados correspondiente al trámite N° <span class="uppercase font-bold">{!! $code !!}</span>  fueron revisados en su versión digital por el personal de Archivo y Gestión documental de Beneficios Económicos. Dicha revisión incluye la verificación de su correcta indexación en el sistema informático de tramites y calidad de imagen, en relación con el titular y/o beneficiario señor(a) </span><br>
                </div><br>

        <div class="font-bold uppercase m-b-5 counter">
            Datos del solicitante
        </div>
        @include('eco_com.print.applicant_info', ['applicant'=>$eco_com_beneficiary])
        <div class="font-bold uppercase m-b-5 counter">
            Datos Policiales del Titular
        </div>
        @include('eco_com.print.only_police_info', ['affiliate'=>$affiliate])

        @if(sizeof($eco_com_submitted_documents) > 0)
            <div class="font-bold uppercase m-b-5  m-t-5 counter">DOCUMENTOS</div>
            <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm">
                        <td class="text-center p-5">N°</td>
                        <td class="text-center p-5">REQUISITOS</td>
                        <td class="text-center p-5"  style="width: 100px">PRESENTADOS</td>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($eco_com_submitted_documents as $item)
                    @if($item->number > 0)
                    <tr>
                        <td class='text-center p-5'>{!! $item->number !!}</td>
                        <td class='text-justify p-5'>{!! $item->procedure_document->name !!}
                        @if(trim($item->comment) != null && trim($item->comment) != '')
                            <span class="text-justify text-xs"> <i>* ({!! $item->comment !!})</i></span>
                        @endif
                        </td>
                        @if(!$item->is_uploaded)
                        <td class="text-center">
                            <img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                        </td>
                        @endif
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            @if($eco_com_submitted_documents[0]->number == 0)
            <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm">
                        <td class="text-center p-5">DOCUMENTOS ADICIONALES</td>
                        <td class="text-center p-5" style="width: 100px">PRESENTADOS</td>

                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($eco_com_submitted_documents as $i=>$item) @if($item->number == 0)
                    <tr>
                        <td class='text-justify p-5'>{!! $item->procedure_document->name !!} </td>
                        @if(!$item->is_uploaded)
  
                        <td class="text-center">
                            <img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                        </td>

                        @endif
                    </tr>
                    @endif @endforeach
                </tbody>
            </table>
            @endif
        @endif
            
        <br>
                <div style="page-break-inside: avoid; margin-top: 50px;">
                    @if($eco_com->eco_com_reception_type_id == 2 || $eco_com->eco_com_reception_type_id == 3)
                    <table style="margin-top: {{$size_down}}px;" class="m-t-50 table-info signature-block-margin">
                            <tr>
                                <td class="no-border text-center text-base w-20 align-bottom"
                                    style="border-radius: 0.5em 0 0 0!important;">
                                    <span class="font-bold">
                                        ----------------------------------------------------
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="no-border text-center text-base py-10 w-33 align-top"
                                    style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                                    <span class="font-bold uppercase">{!! $user->fullName() !!}</span>
                                    <br />
                                    <span class="font-bold">{{ $user->position }}</span>
                                </td>
                            </tr>
                    </table>
                    @endif
                </div>
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