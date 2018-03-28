@extends('print_global.print')
@section('content')

<div>
    El suscrito Encargado de  Cuentas Individuales en base a una revisión de la Base de Datos del Sistema Informático de MUSERPOL de aportes realizados, del señor: 
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
    <?php $num=0; ?>
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
            @if($contribution->contribution_type_id == '1')
            
                @foreach($reimbursements as $reimbursement)
                    <tr class="text-sm">
                        <td class="text-center uppercase font-bold px-5 py-3">{{ $num=$num+1}}</td>
                        <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                        <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($contribution->month_year)) }}</td>
                        <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->gain }}</td>
                        <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->base_wage }}</td>
                        <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->seniority_bonus }}</td>                        <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                    </tr>
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
            @endif
        @endforeach           
    </tbody>
</table>
<br>
<div>
    Es cuanto se certifica los últimos 60 salarios efectivamente percibidos previos al destino a la disponibilidad de las letras, para fines consiguientes.
</div>
<br>
<div align="right">
    {{ "Lugar y fecha: ". $place->name." ".$dateac }}
</div>
Cc: Arch
@endsection
