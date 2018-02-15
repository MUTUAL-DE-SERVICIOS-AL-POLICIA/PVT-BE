@extends('print_global.print')
@section('content')
    <div>
        <div class="title2">
            <span class="font-bold">Trámite Nº:</span> {{ $number }}
        </div>
        @include('ret_fun.print.applicant_info', ['applicant'=>$applicant])
        <div>
            <div class="text-left mx-10 block">
                <span class="capitalize">Señor:</span><br>
                <span class="uppercase">CNL. DESP. JHONNY DONATO CORONEL AYALA</span><br>
                <span class="uppercase font-bold">DIRECTOR GENERAL EJECUTIVO</span>
                <span class="uppercase font-bold">MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</span><br>
                <span class="font-bold capitalize">presente.-</span><br>
            </div>
            <div class="text-right block">
                <span class="font-bold uppercase">REF: <span class="underline">SOLICITUD DEL BENEFICIO DE FONDO DE RETIRO POR {!! $modality !!}</span></span>
            </div>
            <p>Distinguido Director:</p>
            <p>Para tal efecto, adjunto folder con los requisitos exigidos de acuerdo al siguiente detalle:</p>
        </div>
    </div>
    <table class="w-100 table-collapse">
        <tr>
            <th colspan="3" class="border text-center py-4 bg-grey-darker"><b>DOCUMENTOS RECEPCIONADOS<b></th>
        </tr>
        <tr>
            <th class="border p-5"><strong>N°</strong></th>
            <th class="border p-5"><strong>REQUISITOS</strong></th>
            <th class="border p-5"><strong>V°B°</strong></th>
        </tr>
        @foreach($submitted_documents as $i=>$item)
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
        @endforeach
    </table>
    <p class="text-justify">Declaro que toda la documentación presentada es veraz y fidedigna, y en caso de demostrarse cualquier falsedad, distorsión u omisión en la documentación, reconozco y asumo que la Unidad de Fondo de Retiro Policial Solidario procederá a la anulación del trámite y podrá efectuar las acciones correspondientes conforme el Parágrafo II, artículo 44 del Reglamento de Fondo de Retiro Policial Solidario.</p>
    <table class="m-b-20">
        <tr>
            <th class="no-border text-center" style=" width:60%">
                <p class="font-bold">----------------------------------------------------<br>
                {!! $applicant->last_name." ".$applicant->first_name !!}<br/> C.I. {!! $applicant->identity_card !!} {!! $applicant->city_identity_card->first_shortened!!}
                </p>
            </th>
        </tr>
    </table>

</div>
@endsection
