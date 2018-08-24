@extends('print_global.print') 
@section('content')
<div>
    <div style="min-height:900px;height:900px; max-height:900px;">
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Trámite
        </div>
        @include('ret_fun.print.info',['retirement_fund'=>$retirement_fund])
        <div class="font-bold uppercase m-b-5 counter">
            Datos del solicitante
        </div>
        @include('print_global.applicant_info', ['applicant'=>$applicant])
        <div class="font-bold uppercase m-b-5 counter">
            Datos Policiales del Titular
        </div>
            @include('print_global.only_police_info', ['affiliate'=>$affiliate])
        <div>
            <div class="text-left block">
                <span class="capitalize">Señor:</span><br>
                <span class="uppercase">CNL. DESP. JHONNY DONATO CORONEL AYALA</span><br>
                <span class="uppercase font-bold">DIRECTOR GENERAL EJECUTIVO</span>
                <span class="uppercase font-bold">MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</span><br>
                <span class="font-bold capitalize">presente.-</span><br>
            </div>
            <div class="text-right block">
                <span class="font-bold uppercase">REF: <span class="underline">SOLICITUD DEL BENEFICIO DE FONDO DE RETIRO POR {!! $modality !!}</span></span>
            </div>
            <div class="m-b-5">Distinguido Director:</div>
            <div class="m-b-10">Para tal efecto, adjunto folder con los requisitos exigidos de acuerdo al siguiente detalle:</div>
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
                @foreach($submitted_documents as $i=>$item)
                    @if($item->procedure_requirement->number > 0)
                        <tr>
                            <td class='text-center p-5'>{!! $item->procedure_requirement->number !!}</td>
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
                                {{-- <td class='text-center p-5'>{!! $item->procedure_requirement->number !!}</td> --}}
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
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="text-justify text-sm">Declaro que toda la documentación presentada es veraz y fidedigna, y en caso de demostrarse cualquier falsedad, distorsión
            u omisión en la documentación, reconozco y asumo que la Unidad de Fondo de Retiro Policial Solidario procederá a
            la anulación del trámite y podrá efectuar las acciones correspondientes conforme el Parágrafo II, artículo 44 del
            Reglamento de Fondo de Retiro Policial Solidario.</div>
        <table class="m-t-35">
            <tr>
                <td class="no-border text-center text-base w-50 align-bottom">
                    <span class="font-bold">
                        ----------------------------------------------------
                    </span>
                </td>
                <td class="no-border text-center text-base w-50 align-bottom">
                    <span class="font-bold">
                        ----------------------------------------------------
                    </span>
                </td>
            </tr>
            <tr>
                <td class="no-border text-center text-base w-50 align-top">
                    <span class="font-bold">{!! strtoupper($applicant->fullName()) !!}</span>
                    <br/>
                    <span class="font-bold">C.I. {!! $applicant->identity_card !!} {!! strtoupper($applicant->city_identity_card->first_shortened)!!}</span>
                </td>
                <td class="no-border text-center text-base w-50">
                    <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                    <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">{!! $user->position !!}</div>
                </td>
            </tr>
        </table>
        </ol>
    </div>
</div>
@endsection