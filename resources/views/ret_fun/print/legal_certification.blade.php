@extends('print_global.print')
{{--  @section('title2')

@endsection  --}}
@section('content')
<div>
    <div>
        {{--  @include('print.aplicant_info')  --}}
    </div>
    <span>
        <strong>MOTIVO:</strong>Fondo de Retiro
    </span>
    <table>
        {{--  <tr>
            <th colspan="3" class="grand service"><b>DOCUMENTOS RECEPCIONADOS<b></th>
        </tr>  --}}
        <tr align="center">
            <th class="w-10"><strong>N°</strong></th>
            <th class="w-80"><strong>DOCUMENTACIÓN PRESENTADA</strong></th>
            <th class="w-10"><strong>V°B°</strong></th>
        </tr>
        {{--  @foreach($submitted_documents as $i=>$item)
            <tr>
                <td class='border text-center p-5'>{!! $item->procedure_requirement->number !!}</td>
                <td class='border text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
                @if (true)
                    <td class="border text-center p-5">
                        <img class="circle" src="{{asset('images/check.png')}}" >
                    </td>
                @else
                    <td class="info" style='text-align:center;'>
                        <img class="circle" src="images/uncheck.png" style="width:60%" alt="icon">  
                    </td>
                @endif
            </tr>
        @endforeach  --}}
    </table>
    <p class="text-justify border p-10">
        El Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de acuerdo al
        numeral 2 del artículo 45 del Reglamento de Fondo de Retiro Policial Solidario, <strong>CERTIFICA</strong> que la documentación presentada
        es <strong>VALIDA</strong>.
    </p>    
</div>
@endsection
