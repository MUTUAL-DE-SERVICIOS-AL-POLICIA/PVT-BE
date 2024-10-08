@extends('print_global.print')
@section('content')
<div>
    <div style="min-height:900px;height:900px; max-height:900px;">
        <div class="font-bold uppercase m-b-5 counter m-t-15">
            Datos del Trámite
        </div>
        @include('ret_fun.print.info',['retirement_fund'=>$retirement_fund])
        <div class="font-bold uppercase m-b-5 counter">
            Datos del solicitante
        </div>
        @include('print_global.applicant_info', ['applicant'=>$applicant])
      @if($applicant->kinship->name != 'Titular')
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Titular
        </div>
            @include('ret_fun.print.police_info', ['affiliate'=>$affiliate])
        @endif
        <div class="font-bold uppercase m-b-5 counter">
            Datos Policiales del Titular
        </div>
        @include('ret_fun.print.only_police_info', ['affiliate'=>$affiliate,'procedure_modality'=>$retirement_fund->procedure_modality])
        <div class="m-t-15">
            <div class="text-left block">
                <span class="capitalize">Señor:</span><br>
                <span>CNL. MSc. CAD. LUCIO ENRIQUE RENÉ JIMÉNEZ VARGAS</span><br>
                <span class="uppercase font-bold">DIRECTOR GENERAL EJECUTIVO</span>
                <span class="uppercase font-bold">MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</span><br>
                <span class="font-bold capitalize">presente.-</span><br>
            </div>
            @php($article_by = $retirement_fund->procedure_modality->id==62 ? 'AL': 'POR')
            <div class="text-right block m-t-5 m-b-5">
                <span class="font-bold uppercase">REF: <span class="underline">SOLICITUD {{$article}} {{ $retirement_fund->procedure_modality->procedure_type->name }} {{$article_by}} {!! $modality !!}</span></span>
            </div>
            <div class="m-b-5">Distinguido Director:</div>
            <div class="m-b-10">Para tal efecto, adjunto folder con los requisitos exigidos de acuerdo al siguiente detalle:</div>
        </div>
        <div class="font-bold uppercase m-b-5 counter">DOCUMENTOS RECEPCIONADOS</div>
        <table class="table-info w-100 m-b-5">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-xs">
                    <td class="text-center p-5">N°</td>
                    <td class="text-center p-5">REQUISITOS</td>
                    <td class="text-center p-5">V°B°</td>
                </tr>
            </thead>
            <tbody class="text-xs">
                @foreach($submitted_documents as $i=>$item)
                    @if($item->procedure_requirement->number > 0)
                        <tr>
                            <td class='text-center p-5'>{!! $item->procedure_requirement->number !!}</td>
                            <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
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
                    @endif
                @endforeach
            </tbody>
        </table>
        @if($submitted_documents[0]->procedure_requirement->number != 1)
            <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-xs">
                        {{-- <td class="text-center p-5">N°</td> --}}
                        <td class="text-center p-5">ADICIONALES</td>
                        <td class="text-center p-5">V°B°</td>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach($submitted_documents as $i=>$item)
                        @if($item->procedure_requirement->number == 0)
                            <tr>
                                {{-- <td class='text-center p-5'>{!! $item->procedure_requirement->number !!}</td> --}}
                                <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
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
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="text-justify text-xxs">Declaro que toda la documentación presentada es veraz y fidedigna, en caso de demostrarse cualquier falsedad, distorsión u omisión en la documentación, reconozco y asumo que la Unidad de Fondo de Retiro Policial
            Solidario procederá a la anulación del trámite y podrá efectuar las acciones correspondientes conforme al Artículo 49 del Reglamento de Fondo de Retiro Policial Solidario.</div>
        <div class="text-justify text-xxs">{{$legend_ret_fun}}
        </div>
        <!-- <div class="text-justify text-xxs">En mi calidad de solicitante del beneficio, autorizo de forma expresa a la MUSERPOL para notificarme con el desembolso del beneficio por medio de mensaje de texto al celular detallado en el presente formulario.</div>
        <div class="text-justify text-xxs">El trámite iniciado, será desembolsado en su cuenta bancaria en el plazo de 12 días hábiles, iniciando a partir de este momento, el plazo de 10 días hábiles para presentar la solicitud de revisión del monto calificado.
            Posterior a este plazo y de no registrarse ninguna solicitud de revisión en este plazo de tiempo, se procederá a la ejecutoría de la liquidación de pago
        </div> -->
        <table class="m-t-5">
            <tr>
                <td class="no-border text-center text-base w-50 align-top">
                    <br><br>
                    <span class="font-bold">
                        ----------------------------------------------------
                    </span>
                    <span class="font-bold block">{!! strtoupper($applicant->fullName()) !!}</span>
                    <span class="font-bold">C.I. {!! $applicant->identity_card !!} </span>
                </td>
                <td class="no-border text-center text-base w-50 align-top">
                    <br><br>
                    <span class="font-bold">
                        ----------------------------------------------------
                    </span>
                    <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                    <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">{!! $user->position !!}</div>
                </td>
            </tr>
        </table>
        </ol>
    </div>
</div>
@endsection