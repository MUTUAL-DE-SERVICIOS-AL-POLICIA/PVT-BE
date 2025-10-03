@extends('print_global.print')
@section('content')
<br>
<div>
    El suscrito Encargado de Cuentas Individuales en base a una revisión de la Base de Datos del Sistema Informático de MUSERPOL de aportes realizados, del señor: 
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
                TOTAL COTIZABLE
            </td>
            <td class="px-15 py text-center">
                APORTE F.R.P.S
            </td>
            <td class="px-15 py text-center">
                APORTE C.M.
            </td>
            <td class="px-15 py text-center">
                TOTAL APORTE
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
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->quotable) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->retirement_fund) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->mortuary_quota) }}</td>                        
                    <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->total) }}</td>
                </tr> 
                @foreach($reimbursements as $reimbursement)
                    @if($contribution->month_year == $reimbursement->month_year)       
                        <tr class="text-sm">
                            <td class="text-center uppercase font-bold px-5 py-3"></td>
                            <td class="text-center uppercase font-bold px-5 py-3">Ri</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($reimbursement->month_year)) }}</td>                            
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->gain) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->quotable) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->retirement_fund) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->mortuary_quota) }}</td>                        
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($reimbursement->total) }}</td>                           
                        </tr>
                    @endif        
                @endforeach
        @endforeach    
    </tbody>
</table>
<br>
<div>
    Es cuanto se certifica para fines consiguientes
</div>
<br>
@if($message)
    <div>
        <strong>Nota:</strong>
        <div class="text-justify">
            {{ $message }}
        </div>
    </div>
@endif 
@include('ret_fun.print.signature_footer',['user'=>$user])
Cc: Arch
@endsection
