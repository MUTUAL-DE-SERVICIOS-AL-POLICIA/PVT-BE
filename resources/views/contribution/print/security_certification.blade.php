@extends('print_global.print')
@section('content')
<div>
    El suscrito Encargado de  Cuentas Individuales en base a una revisión manual de planillas de pago de haberes del Batallón de Seguridad Física Privada La Paz, del señor:
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
                    @if($contribution->contribution_type_id == $security_contributions->id)
                    <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($contribution->gain) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($contribution->base_wage) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ \Muserpol\Helpers\Util::formatMoney($contribution->seniority_bonus) }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $contribution->total }}</td>
                    @else
                    <td class="text-center uppercase font-bold px-5 py-3" colspan="4">NO APORTE</td>
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
        {{-- <tr>
            <td colspan="6" class="text-center">TOTAL:</td>
            <td class="text-center uppercase font-bold px-5 py-3" >{{ $total }}</td>   
        </tr>          --}}
    </tbody>
</table>
<br>
<br>
<div>
    <b>CONVENIO.-</b> Según Convenio el personal de Seguridad y Administrativo del Batallón de Seguridad Física Privada La Paz, efectuó un aporte laboral del 3% a la Mutual de Seguros del Policía "MUSEPOL", a partir de Junio del  2002.
</div>
<br>
<div>
    <b>INCORPORACI&Oacute;N</b>  Por Resolución Suprema No. 227336 de fecha 21 de Mayo de 2007, los Batallones de Seguridad  Física  Privada La Paz  y  del  Interior  de la  República,són  incorporados al Escalafón Unico del Comando General de la Policía Boliviana, a partir de Mayo del Año 2007.							

</div>
<br>
<div>
    De la revisión realizada se pudo establecer que el funcionario realizo {{$contributions_number}} aportes en la institucion hasta abril de 2007. 
    <br>
    Es cuanto se certifica para  fines consiguientes. 
</div>
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
