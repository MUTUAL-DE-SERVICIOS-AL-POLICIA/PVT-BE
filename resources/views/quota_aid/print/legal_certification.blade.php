@extends('print_global.print')
@section('content')
<div>
    <div class="font-bold uppercase m-b-5 counter">
        Datos del Trámite
    </div>
    @include('quota_aid.print.info',['quota_aid'=>$quota_aid])
    <div class="font-bold uppercase m-b-5 counter">
        Datos del titular
    </div>
    <div>
        @include('affiliates.print.info', ['affiliate' => $affiliate ])
    </div>
    <p>
        <strong>MOTIVO: </strong>
        <span class="uppercase">
            {{$quota_aid->procedure_modality->procedure_type->name }} por  {{ $quota_aid->procedure_modality->name }}
        </span>
    </p>
    <div class="font-bold uppercase m-b-5 counter">
        DOCUMENTACIÓN PRESENTADA
    </div>
    <table class="w-100 table-info">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm">
                <td class="w-10 text-center">N°</td>
                <td class="w-80 text-center">DOCUMENTACIÓN PRESENTADA</td>
                <td class="w-10 text-center">V°B°</td>
            </tr>
        </thead>
        <tbody>
            @foreach($submitted_documents as $i=>$item)
                @if($item->procedure_requirement->number > 0)
                    <tr>
                        <td class='text-center p-5'>{{$item->procedure_requirement->number}}</td>
                        <td class='text-justify p-5'>{{$item->procedure_requirement->procedure_document->name}} </td>
                        @if ($item->is_valid)
                        <td class="text-center">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                        </td>
                        @else
                        <td class="text-center">
                                <i class="mdi mdi-close-box-outline mdi-24px"></i>
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
                    <tr class="font-medium text-white text-sm">
                        {{-- <td class="text-center p-5">N°</td> --}}
                        <td class="text-center p-5">ADICIONALES</td>
                        <td class="text-center p-5">V°B°</td>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($submitted_documents as $i=>$item)
                        @if($item->procedure_requirement->number == 0)
                            <tr>                                
                                <td class='text-justify p-5'>{{$item->procedure_requirement->procedure_document->name}} </td>
                                @if ($item->is_valid)
                                <td class="text-center">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                                </td>
                                @else
                                <td class="text-center">
                                        <i class="mdi mdi-close-box-outline mdi-24px"></i>
                                </td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif

    <p class="text-justify rounded border p-10">
        El Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de acuerdo al 
        numeral 3 del artículo 52 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio, <strong>CERTIFICA</strong> que la documentación presentada es 
        <strong>VALIDA</strong>.
    </p>
    <div class="text-center w-100 m-t-50">
            <span class="font-bold block">--------------------------------------------------</span>
            <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
            <div class="text-xs text-center" style="width: 350px; margin:0 auto">{!! $user->position !!}</div>
        </div>
    </div>
</div>
@endsection
