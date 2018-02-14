@extends('print_global.print')
{{--  @section('title2')

@endsection  --}}
@section('content')
    <div>
    @include('ret_fun.print.applicant_info')

    <span>Señor:
            CNL. DESP. JHONNY DONATO CORONEL AYALA<br>
            <strong>
                DIRECTOR GENERAL EJECUTIVO<br>
                MUTUAL DE SERVICIOS AL POLICÍA “MUSERPOL”<br>
                Presente.-<br>
            </strong>
            <div align="right">
                <strong>
                    REF: 
                    <u>
                        SOLICITUD DEL BENEFICIO DE FONDO DE RETIRO POR {!! $title !!}
                    </u><br>
                </strong>
            </div>
            Distinguido Director:<br><br>
            Para tal efecto, adjunto folder con los requisitos exigidos de acuerdo al siguiente detalle:<br><br>
            </span>
    
    <table align="center">
            {{--  <tr>
                <th colspan="3" class="grand service"><b>DOCUMENTOS RECEPCIONADOS<b></th>
            </tr>  --}}
        <tr align="center">
            <th width="5%"><strong>N°</strong></th>
            <th width="85%"><strong>REQUISITOS</strong></th>
            <th width="10%"><strong>V°B°</strong></th>
        </tr>
        @foreach($submitted_documents as $i=>$item)
            <tr>
                <td style='text-align:center;'> <h3>{!! $item->procedure_requirement->number !!}</h3></td>
                <td style='text-align:center;'> <h3>{!! $item->procedure_requirement->procedure_document->name !!} </h3></td>
                @if (true)
                    <td class="info" style='text-align:center;'>
                        <img class="circle" src="{{asset('images/check.png')}}" style="width:70%" alt="icon">
                    </td>
                @else
                    <td class="info" style='text-align:center;'>
                        <img class="circle" src="images/uncheck.png" style="width:60%" alt="icon">  
                    </td>
                @endif
            </tr>
        @endforeach
    </table>
    <table class="no-border">
            <td>
                Declaro que toda la documentación presentada es veraz y fidedigna, y en caso de demostrarse cualquier falsedad,
                distorsión u omisión en la documentación, reconozco y asumo que la Unidad de Fondo de Retiro Policial Solidario
                procederá a la anulación del trámite y podrá efectuar las acciones correspondientes conforme el Parágrafo II,
                artículo 44 del Reglamento de Fondo de Retiro Policial Solidario.
            </td>
    </table>
    <table>
        <tr>
            <th class="info" style="border: 0px;text-align:center;"><p>&nbsp;</p><br>
                -------------------------------------------
            </th>
        </tr>
        <tr>
            <th class="info" style="border: 0px;text-align:center;">
                <b>
                    {!! $applicant->last_name." ".$applicant->first_name !!}<br/>
                    C.I. {!! $applicant->identity_card !!} {!! $applicant->city_identity_card->first_shortened !!} <br/>
                    {{-- Teléfono: {!! $eco_com_applicant->getPhone() !!} --}}
                </b>
            </th>        
        </tr>
    </table>
</div>
@endsection

@section('footer')
   @include('print_global.footer')
@endsection