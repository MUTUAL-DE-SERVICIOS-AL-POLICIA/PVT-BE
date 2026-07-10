@extends('print_global.print')
@section('content')
<div>
    El suscrito Encargado de  Cuentas Individuales en base a una revisión de la Base de Datos del Sistema Informático de MUSERPOL de aportes realizados, del señor: 
</div>
<div class="my-10">
@include('print_global.police_info', ['affiliate' => $affiliate, 'degree' => $degree, 'exp' => $exp ])
</div>
<strong>CERTIFICA:</strong>  
<table class="table-info w-100 my-10">        
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm uppercase">
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
                Antigüedad
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
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->gain > 0 ? Util::formatMoney($contribution->gain) : Util::formatMoney($contribution->base_wage) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->base_wage) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->seniority_bonus) }}</td>                        
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->total) }}</td>
                </tr> 
                @foreach($reimbursements as $reimbursement)
                    @if($contribution->month_year == $reimbursement->month_year)       
                        <tr class="text-sm">
                            <td class="text-center uppercase font-bold px-5 py-3"></td>
                            <td class="text-center uppercase font-bold px-5 py-3">Ri</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($reimbursement->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->gain > 0 ? Util::formatMoney($reimbursement->gain) : Util::formatMoney($reimbursement->base_wage) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->base_wage) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->seniority_bonus) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->total) }}</td>
                        </tr>
                    @endif        
                @endforeach 
               
        @endforeach    

    </tbody>
</table>
<br>
<div>
    @if($retirement_fund->procedure_modality->procedure_type_id == 1)
        Es cuanto se certifica los últimos {{ sizeof($affiliate->getContributionsPlus()) }} salarios efectivamente percibidos con registro de aportes para el beneficio de Fondo de Retiro Policial Solidario.
    @else     
        @if($affiliate->hasAvailability())
            Es cuanto se certifica los últimos 60 salarios efectivamente percibidos previos al destino a la disponibilidad de las letras, para fines consiguientes.
        @else
            Es cuanto se certifica los últimos 60 salarios efectivamente percibidos con registro de aportes para el beneficio de Fondo de Retiro Policial Solidario.
        @endif 
        
    @endif

    
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
@include('ret_fun.print.signature_footer',['user'=>$user])
Cc: Arch
@endsection
