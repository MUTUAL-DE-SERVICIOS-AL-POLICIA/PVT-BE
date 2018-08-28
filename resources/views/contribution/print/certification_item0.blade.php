@extends('print_global.print')
@section('content')
  
<div>
        El suscrito Encargado de  Cuentas Individuales en base a una revisión de la Base de Datos del Sistema Informático de MUSERPOL de aportes realizados por funcionarios en comisión de Item "0", del señor:
</div><br>
@include('print_global.police_info', ['affiliate' => $affiliate, 'degree' => $degree, 'exp' => $exp ])
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
            @if($contribution->contribution_type_id == $itemcero->id)
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->quotable }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->retirement_fund }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->mortuary_quota }}</td>                        
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->ipc }}</td>
                    {{-- con la bd devretfun
                        <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->interest }}</td>--}}
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                </tr> 
                @foreach($reimbursements as $reimbursement)    
                    @if($contribution->month_year == $reimbursement->month_year)
                        <tr class="text-sm">
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($reimbursement->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->quotable }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->total }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->retirement_fund }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->mortuary_quota }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->ipc }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $reimbursement->total }}</td>
                        </tr>
                    @endif        
                @endforeach
            @endif
            @if($contribution->contribution_type_id == $itemcero_sin_aporte->id)
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($contribution->month_year)) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->quotable }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->retirement_fund }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->mortuary_quota }}</td>                        
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->ipc }}</td>
                    {{-- con la bd devretfun
                        <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->interest }}</td>--}}
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                </tr> 
                
            @endif
        @endforeach
    </tbody>
</table>
<br>
@if($retirement_fund->contribution_types()->where('contribution_type_id', 2)->first())
    <div>
        <strong>Nota:</strong>
        <div class="text-justify">
            {{ $retirement_fund->contribution_types()->where('contribution_type_id', 2)->first()->pivot->message }}
        </div>
    </div>
@endif

<div align="right">
    {{ "Lugar y fecha: ". $place->name.", ".$dateac }}
</div>
Cc: Arch
@endsection