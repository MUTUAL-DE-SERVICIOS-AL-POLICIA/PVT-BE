@extends('print_global.print',['unit' => ''])
{{--  @section('title2')

@endsection  --}}
@section('content')
<div>
    <p>El suscrito Encargado de Archivo de Beneficios Económicos:</p>
    <p class="font-bold uppercase">CERTIFICA QUE:</p>
            <p class="text-justify">
                Iniciado el Trámite de Fondo de Retiro Policial Solidario <strong>N° {{$retirement_fund->code}} </strong>de Ventanilla de Atención al
                Afiliado, se realizó la revisión y verificación de antecedentes en base a los datos que figuran en el
                expediente presentado en favor del titular señor (a):
            </p>
            <div>
                @include('affiliates.print.info', ['affiliate'=>$affiliate])
            </div>
            <ol class="list-roman">
                <li><strong>ANTECEDENTES DE CARPETA</strong></li>
                <p>
                    Estableciendo que <strong>@if(sizeof($affiliate_folders)==0)NO @else SI @endif</strong> existe expediente del referido.<br>
                    Tipo de tr&aacute;mite cancelado:
                </p>
                @if(sizeof($affiliate_folders)>0)
                <table class="w-100 table-info m-b-10">
                    <thead class="bg-grey-darker">
                        <tr class="font-medium text-white text-sm">
                            <th class="w-40"><strong>TIPOS DE BENEFICIOS</strong></th>
                            <th class="w-20"><strong>SIGLA</strong></th>            
                            <th class="w-10"><strong>EXISTE</strong></th>
                            <th class="w-10"><strong>PAGADO</strong></th>
                            <th class="w-20"><strong>NOTA</strong></th>
                        </tr>
                    </thead>
                    @foreach($affiliate_folders as $i=>$item)
                        <tr>
                            <td class="text-center uppercase font-bold px-5 py-3">{!! $item->procedure_modality->name ?? 'ERROR' !!} </td>
                            <td class="text-center uppercase font-bold px-5 py-3">{!! $item->procedure_modality->shortened ?? 'ERROR' !!}</td>                            
                            <td class="text-center uppercase font-bold px-5 py-3">SI</td>
                            <td class="text-center uppercase font-bold px-5 py-3">@if($item->is_paid === true) SI @endif @if($item->is_paid === false) NO @endif</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{!! $item->note !!}</td>
                        </tr>
                    @endforeach
                </table>
                @else
                <p class="font-bold">NINGUNO</p>
                @endif
                <li><strong>TRÁMITE ACTUAL PRESENTADO POR {{$retirement_fund->procedure_modality->shortened ?? 'ERROR'}}</strong></li>
                    <p>
                        SOLICITADO POR:
                    </p>
                    @include('print_global.applicant_info', ['applicant' => $applicant ])
            </ol> 
<span>
    Es cuanto certifico para fines consiguientes.
</span>
@include('ret_fun.print.signature_footer',['user'=>$user])
</div>
@endsection
