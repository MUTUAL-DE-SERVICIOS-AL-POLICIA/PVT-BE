@extends('print_global.print')
@section('content')
<div>
    El suscrito Encargado de  Cuentas Individuales en base a una revisión física de las planillas de haberes proporcionadas por el Comando General de la Policía Boliviana, del señor:
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
                    @if($contribution->contribution_type_id == $certification_contribution->id)
                    <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($contribution->gain) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($contribution->base_wage) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($contribution->seniority_bonus) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                    @else
                        @if ($contribution->contribution_type_id == 9)
                            <td class="text-center uppercase font-bold px-5 py-3" colspan="4">PERÍODO NO TRABAJADO</td>
                        @else
                            <td class="text-center uppercase font-bold px-5 py-3" colspan="4">NO APORTE</td>
                        @endif
                    @endif
                </tr>
                @foreach($reimbursements as $reimbursement)
                    @if($contribution->month_year == $reimbursement->month_year)       
                        <tr class="text-sm">
                            <td class="text-center uppercase font-bold px-5 py-3"></td>
                            <td class="text-center uppercase font-bold px-5 py-3">Ri</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($reimbursement->month_year)) }}</td>                            
                                <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($reimbursement->gain) }}</td>
                                <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($reimbursement->base_wage) }}</td>
                                <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($reimbursement->seniority_bonus) }}</td>
                                <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->total }}</td>                            
                        </tr>
                    @endif        
                @endforeach 
               
        @endforeach    
        <tr>
            <td colspan="6" class="text-center">TOTAL:</td>
            <td class="text-center uppercase font-bold px-5 py-3" >{{ $total }}</td>   
        </tr>         
    </tbody>
</table>
<br>
<div>    
    Es cuanto se certifica para  fines consiguientes. 
</div>
@if($retirement_fund->contribution_types()->where('contribution_type_id', 7)->first())
    <div>
        <strong>Nota:</strong>
        <div class="text-justify">
            {{ $retirement_fund->contribution_types()->where('contribution_type_id', 7)->first()->pivot->message }}
        </div>
    </div>
@endif
<br>
<div align="right">
    {{ "Lugar y fecha: ". $place->name.", ".$dateac }}
</div>
Cc: Arch
<table class="m-t-35">
    <tr>            
        <th class="no-border text-center" style=" width:50%">
            <p class="font-bold">----------------------------------------------------<br> {!! strtoupper($user->fullName()) !!}<br/> {!! $user->position !!}
            </p>
        </th>
    </tr>
</table>
@endsection
