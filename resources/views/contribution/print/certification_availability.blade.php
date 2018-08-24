@extends('print_global.print')
@section('content')
  
<div>
    El suscrito Encargado de  Cuentas Individuales en base a una revisión de la Base de Datos del Sistema Informático de MUSERPOL de aportes realizados, del señor:
</div><br>
@include('print_global.police_info', ['affiliate' => $affiliate, 'degree' => $degree, 'exp' => $exp ])
<strong>CERTIFICA:</strong>
<table class="table-info w-100">        
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-15 py text-center ">
                Nº
            </td>
            <td class="px-15 py text-center ">
                MES
            </td>        
            <td class="px-15 py text-center">
                AÑO
            </td>
            <td class="px-15 py text-center">
                TOTAL GANADO
            </td>
            <td class="px-15 py text-center">
                BONO SEGURIDAD CIUDADANA
            </td>
            <td class="px-15 py text-center">
                TOTAL APORTE
            </td>
            {{--<td class="px-15 py text-center">
                APORTE FONDO DE RETIRO
            </td>--}}               
        </tr>
    </thead><br>

    <tbody> 
        @foreach($contributions as $contribution)     
            @if($contribution->contribution_type_id == $disponibilidad->id)
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $num=$num+1}}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->gain }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->public_security_bonus }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>                        
                    {{--<td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->retirement_fund }}</td>--}}
                </tr>
                @foreach($reimbursements as $reimbursement)    
                    @if($contribution->month_year == $reimbursement->month_year)
                        <tr class="text-sm">
                            <td class="text-center uppercase font-bold px-5 py-3"></td>
                            <td class="text-center uppercase font-bold px-5 py-3">R1</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($reimbursement->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->gain }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->public_security_bonus }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->total }}</td>
                            {{--<td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->retirement_fund }}</td>--}}
                        </tr>
                    @endif        
                @endforeach
            @endif
        @endforeach
        {{-- <tr>
            <td colspan="5" class="text-center">TOTAL:</td>
            <td class="text-center uppercase font-bold px-5 py-3" >{{$aporte}}</td>   
        </tr>                 --}}
    </tbody>
</table>
<br>
<div>
    Es cuanto se certifica los aportes al destino a la disponibilidad de las letras, para fines consiguientes.
</div>
<br>
<div align="right">
    {{ "Lugar y fecha: ". $place->name.", ".$dateac }}
</div>
Cc: Arch
@endsection