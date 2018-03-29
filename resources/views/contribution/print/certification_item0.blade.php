@extends('print_global.print')
@section('content')
  
<div>
        El suscrito Encargado de  Cuentas Individuales en base a una revisión de la Base de Datos del Sistema Informático de MUSERPOL de aportes realizados por funcionarios en comisión de Item "0", del señor:
</div><br>

<table class="table-info w-100">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-15 py text-center ">
                GRADO
            </td>
            <td class="px-15 py text-center ">
                PRIMER NOMBRE
            </td>
            <td class="px-15 py text-center">
                SEGUNDO NOMBRE
            </td>
            <td class="px-15 py text-center">
                APELLIDO PATERNO
            </td>
            <td class="px-15 py text-center">
                APELLIDO MATERNO
            </td>
            <td class="px-15 py text-center">
                C.I.
            </td>
            <td class="px-15 py text-center">
                EXP.
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="text-sm">
            <td class="text-center uppercase font-bold px-5 py-3">{{ $degree->name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->first_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->second_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->mothers_last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->identity_card }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $exp }}</td>
        </tr>
    </tbody>
</table><br>
<strong>CERTIFICA:</strong>
<table class="table-info w-100">        
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-15 py text-center ">
                MES
            </td>
            <td class="px-15 py text-center ">
                AÑO
            </td>        
            <td class="px-15 py text-center">
                COTIZABLE
            </td>
            <td class="px-15 py text-center">
                APORTE
            </td>
            <td class="px-15 py text-center">
                F.R.P.
            </td>
            <td class="px-15 py text-center">
                CUOTA MORTORIA
            </td>
            <td class="px-15 py text-center">
                AJUSTES
            </td> 
            <td class="px-15 py text-center">
                TOTAL APORTES
            </td>              
        </tr>
    </thead><br>

    <tbody> 
        @foreach($contributions as $contribution)     
            @if($contribution->contribution_type_id == '3')
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->quotable }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->retirement_fund }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->mortuary_quota }}</td>                        
                    <td class="text-center uppercase font-bold px-5 py-3"></td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                </tr> 
                @foreach($reimbursements as $reimbursement)    
                    @if($contribution->month_year == $reimbursement->month_year)
                        <tr class="text-sm">
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($reimbursement->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->quotable }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->retirement_fund }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->mortuary_quota }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3"></td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->total }}</td>
                        </tr>
                    @endif        
                @endforeach
            @endif
        @endforeach               
    </tbody>
</table>
<br>

<div align="right">
    {{ "Lugar y fecha: ". $place->name.", ".$dateac }}
</div>
Cc: Arch
@endsection