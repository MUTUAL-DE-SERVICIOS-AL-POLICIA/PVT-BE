@extends('print_global.print')
@section('content')
<div>
        El suscrito Encargado de Cuentas Individuales a solicitud del (la) Calificador (a) de Fondo de Retiro, Cuota Y Auxilio Mortuorio, y en base a una revisi&oacute;n en la Plataforma Virtual MUSERPOL, 
        @if($affiliate->gender == "M")
            del señor: 
        @else 
            de la señora: 
        @endif
</div>
<div class="my-10">
@include('print_global.police_info', ['affiliate' => $affiliate, 'degree' => $degree, 'exp' => $exp ])
</div>
<strong>CERTIFICA:</strong> Que los &uacute;ltimos doce (12) aportes establecidos en el Reglamento de Couta Mortuoria y Auxilio Mortuorio, corresponden al siguiente detalle:
<table class="table-info w-100 my-10">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm uppercase">                            
            <td class="text-center uppercase font-bold px-5 py-3">MODALIDAD</td>
        </tr>
    </thead>
    <tr class="text-sm" >                                    
            <td class="text-center uppercase font-bold px-5 py-3"> {!! $quota_aid->procedure_modality->name !!}  </td>    
    </tr>
</table>

<table class="table-info w-100 my-10">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm uppercase">            <td class="px-15 py text-center ">
                Nº
            </td>
            <td class="px-15 py text-center ">
                GESTI&Oacute;N
            </td>        
            <td class="px-15 py text-center">
                MES
            </td>
            <td class="px-15 py text-center">
                Grado
            </td>
            <td class="px-15 py text-center">
                TOTAL GANADO
            </td>
            <td class="px-15 py text-center">
                TOTAL COTIZABLE
            </td>            
            <td class="px-15 py text-center">
                APORTE 
            </td>               
        </tr>
    </thead><br>
    <tbody> 
        @foreach($contributions as $contribution)       
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $num=$num+1}}</td>                    
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->degree->shortened??$affiliate->degree->shortened }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->gain > 0 ? Util::formatMoney($contribution->gain) : Util::formatMoney($contribution->base_wage) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->quotable) }}</td>                    
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->mortuary_quota) }}</td>
                </tr> 
                @foreach($reimbursements as $reimbursement)
                    @if($contribution->month_year == $reimbursement->month_year)       
                        <tr class="text-sm">                            
                            <td class="text-center uppercase font-bold px-5 py-3">Ri</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($reimbursement->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($reimbursement->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->degree->shortened??$affiliate->degree->shortened  }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->gain > 0 ? Util::formatMoney($reimbursement->gain) : Util::formatMoney($reimbursement->base_wage) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->quotable) }}</td>                            
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->mortuary_quota) }}</td>
                        </tr>
                    @endif     
                @endforeach                                                
        @endforeach    
        <tr class="text-sm">
            <td colspan="7" class="text-center uppercase font-bold px-5 py-3">TOTAL 12 &Uacute;LTIMOS APORTES</td>      
        </tr>
    </tbody>
</table>
<br>
<div>
    En cuanto el suscrito Encargado de Cuentas Individuales certifica para los fines consiguientes.
</div>
<br>
@include('ret_fun.print.signature_footer',['user'=>$user])
Cc: Arch
@endsection
