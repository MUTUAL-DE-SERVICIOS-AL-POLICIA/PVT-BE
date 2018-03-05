@extends('print_global.print') 
@section('content')
<div>
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
    <table class="table-info w-100">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm font-bold">
                <td colspan="3" class="text-center py-4">DOCUMENTOS RECEPCIONADOS</td>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-grey-lightest font-bold">
                <td class="text-center p-5">N°</td>
                <td class="text-center p-5">REQUISITOS</td>
                <td class="text-center p-5">V°B°</td>
            </tr>
            @foreach($submitted_documents as $i=>$item)
            <tr>
                <td class='text-center p-5'>{!! $item->procedure_requirement->number !!}</td>
                <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
                @if (true)
                <td class="text-center">
                    <i class="mdi mdi-close-box-outline"></i>
                </td>
                @else
                <td class="text-center">
                    <i class="mdi mdi-checkbox-marked-outline mdi-24px"></i>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="text-justify">Declaro que toda la documentación presentada es veraz y fidedigna, y en caso de demostrarse cualquier falsedad, distorsión
        u omisión en la documentación, reconozco y asumo que la Unidad de Fondo de Retiro Policial Solidario procederá a
        la anulación del trámite y podrá efectuar las acciones correspondientes conforme el Parágrafo II, artículo 44 del
        Reglamento de Fondo de Retiro Policial Solidario.</p>
    <table class="m-t-35">
        <tr>
            <th class="no-border text-center" style=" width:60%">
                <p class="font-bold">----------------------------------------------------<br> {!! $applicant->fullName() !!}<br/> C.I. {!! $applicant->identity_card
                    !!} {!! $applicant->city_identity_card->first_shortened!!}
                </p>
            </th>
        </tr>
    </table>
</div>
@endsection