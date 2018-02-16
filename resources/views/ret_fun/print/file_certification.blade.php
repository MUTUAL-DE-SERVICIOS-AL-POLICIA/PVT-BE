@extends('print_global.print')
{{--  @section('title2')

@endsection  --}}
@section('content')
<div>
    <span>
        El suscrito Encargado de Archivo de Beneficios Económicos:<br>
            <strong>
                CERTIFICA QUE:<br>
            </strong>
            <p class="text-justify">
                Iniciado el Trámite de Fondo de Retiro Policial Solidario <strong>N° {{$retirement_fund->code}} </strong>de Ventanilla de Atención al
                Afiliado, se realizó la revisión y verificación de antecedentes en base a los datos que figuran en el
                expediente presentado en favor del titular señor (a):
            </p>
            <div>
                {{--@include('print.aplicant_info')  --}}
            </div>
            <ol class="list-roman">
                <li><strong>ANTECEDENTES DE CARPETA</strong></li>
                <span>
                    Estableciendo que <strong>@if(sizeof($affiliate_folders)==0)NO @else SI @endif</strong> existe expediente del referido.<br>
                    Tipo de tramite cancelado:
                </span>
                @if(sizeof($affiliate_folders)>0)
                <table align="center">
                        {{--  <tr>
                        <th colspan="3" class="grand service"><b>DOCUMENTOS RECEPCIONADOS<b></th>
                        </tr>  --}}
                    <tr align="center">
                        <th class="w-50"><strong>TIPOS DE BENEFICIOS</strong></th>
                        <th class="w-20"><strong>SIGLA</strong></th>
                        <th class="w-10"><strong>EXISTE</strong></th>
                        <th class="w-20"><strong>DESCRIPCIÓN</strong></th>
                    </tr>
                    @foreach($affiliate_folders as $i=>$item)
                        <tr>                            
                            <td style='text-align:center;'> <h3>{!! $item->procedure_modality->name !!} </h3></td>
                            <td style='text-align:center;'> <h3>{!! $item->procedure_modality->shortened !!}</h3></td>
                            <td style='text-align:center;'> <h3>SI</h3></td>
                            <td style='text-align:center;'> <h3>{!! $item->description !!}</h3></td>
                        </tr>
                    @endforeach
                </table>
                @else 
                NINGUNO
                @endif
                <li><strong>TRÁMITE ACTUAL PRESENTADO POR {{$retirement_fund->procedure_modality->shortened}}</strong></li>
                    <span>
                        SOLICITADO POR:
                    </span>
                    <table align="center">
                        <tr align="center">
                            <!--<th class="w-50"><strong>N°</strong></th>-->
                            <th width="20%"><strong>PRIMER NOMBRE</strong></th>
                            <th width="20%"><strong>SEGUNDO NOMBRE</strong></th>
                            <th width="20%"><strong>PRIMER APELLIDO</strong></th>
                            <th width="20%"><strong>SEGUNDO APELLIDO</strong></th>
                            <th width="20%"><strong>APELLIDO DE CASADA</strong></th>
                            <th width="15%"><strong>PARENTESCO O VINCULO CON EL TITULAR</strong></th>
                        </tr>                        
                            <tr>
                                {{--<td style='text-align:center;'> <h3>{!! $i+1 !!}</h3></td>--}}
                                <td style='text-align:center;'> <h3>{!! $applicant->first_name !!} </h3></td>
                                <td style='text-align:center;'> <h3>{!! $applicant->second_name !!} </h3></td>
                                <td style='text-align:center;'> <h3>{!! $applicant->last_name !!} </h3></td>
                                <td style='text-align:center;'> <h3>{!! $applicant->mothers_last_name !!} </h3></td>
                                <td style='text-align:center;'> <h3>{!! $applicant->surname_husband !!} </h3></td>                                
                                <td style='text-align:center;'> <h3>{!! $applicant->kinship->name !!} </h3></td>
                            </tr>                        
                    </table>
              </ol> 
<span>
    Es cuanto certifico para fines consiguientes.
</span>
    
    
</div>
@endsection
