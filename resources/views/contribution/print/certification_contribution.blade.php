@extends('print_global.print')
@section('content')
<div>
    El suscrito Encargado de  Cuentas Individuales en base a una revisión de la Base de Datos del Sistema Informático de MUSERPOL de aportes realizados, del señor: 
</div>
<div>
@include('print_global.police_info', ['affiliate' => $affiliate, 'degree' => $degree, 'exp' => $exp ])
</div>

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
                SUELDO
            </td>
            <td class="px-15 py text-center">
                ANTIGUEDAD
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
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->gain }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->base_wage }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->seniority_bonus }}</td>                        
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                </tr> 
                @foreach($reimbursements as $reimbursement)
                    @if($contribution->month_year == $reimbursement->month_year)       
                        <tr class="text-sm">
                            <td class="text-center uppercase font-bold px-5 py-3"></td>
                            <td class="text-center uppercase font-bold px-5 py-3">Ri</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($reimbursement->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->gain }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->base_wage }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->seniority_bonus }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->total }}</td>
                        </tr>
                    @endif        
                @endforeach 
               
        @endforeach    

    </tbody>
</table>
<br>
<div>
    Es cuanto se certifica los últimos 60 salarios efectivamente percibidos previos al destino a la disponibilidad de las letras, para fines consiguientes.
</div>
<br>
@if($retirement_fund->contribution_types()->where('contribution_type_id', 1)->first())
    <div>
        <strong>Nota:</strong>
        <div class="text-justify">
            {{ $retirement_fund->contribution_types()->where('contribution_type_id', 1)->first()->pivot->message }}
        </div>
    </div>
@endif
<div align="right">
    {{ "Lugar y fecha: ". $place->name.", ".$dateac }}
</div>
Cc: Arch
@endsection
