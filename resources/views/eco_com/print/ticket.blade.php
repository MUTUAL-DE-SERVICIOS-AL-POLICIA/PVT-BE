<div>
    <table>
        <tr class="m-b-15">
            <th style="width:30%"></th>
            <th style="width:40%" class="text-xxs font-bold leading-tight">
            MUTUAL DE SERVICIOS AL POLICÍA
            <br>{!! $direction !!}
            <br>{!! $unit !!}
            <br><span class="uppercase italic">"{{ $eco_com->eco_com_procedure->getTextName() }}{{ ($eco_com->old_eco_com && $eco_com->total_repay > 0) ? ' (reintegro)' :''}}"</span>
            </th>
            <td style="width:30%; text-align:right" class="text-xs uppercase">
            <br><br>
            {{ ($user->city->name) ?? 'La Paz' }}, {!! $date !!} </td>
            </td>
        </tr>
        <br><br>
        <tr>
            <td></td>
            <td colspan="2" style="width:100%; border-bottom: 3px double #22292f;"></td>
        </tr>
    </table>
</div>
<div class="block w-100 m-t-15">
    <div class="main-right">
    <table class="text-xs" style="width:100%;">
        
        <tr>
            <td class="no-border" style="width:35%">
            <strong><em>REGIONAL:</em></strong> <br></td>
            <td class="no-border" style="width:65%"><em>{{ $eco_com->city->name ?? '' }} </em><br></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="no-border" style="width:35%">
                <strong><em>TRAMITE NRO:</em></strong><br> </td>
            <td class="no-border" style="width:65%"><em>{!! $eco_com->code ?? ''!!}</em> <br>
            </td>
            <td>
            
            </td>
        </tr>
        <tr>
            <td class="no-border" style="width:35%">
                <strong><em>MODALIDAD:</em></strong><br>
            </td>
            <td class="no-border" style="width:65%">
            <em>{{ strtoupper($eco_com->eco_com_modality->procedure_modality->name) ?? '' }}</em><br></td>
        </tr>
        <tr>
            <td class="no-border" style="width:37%">
                <strong><em>NOMBRE DEL BENEFICIARIO:</em></strong><br>
            </td>
            <td class="no-border" style="width:63%">
            <em>{{ $eco_com->eco_com_beneficiary->fullName() ?? '' }}</em><br></td>
        </tr>
        <tr >
            <td class="no-border" style="width:35%">
                <strong><em>CEDULA DE IDENTIDAD:</em></strong>
            </td>
            <td class="no-border" style="width:65%">
            <em>{!!$eco_com->eco_com_beneficiary->identity_card !!}<br>
            </em></td>
        </tr>
        <tr>
            <td class="no-border" style="width:35%">
                <strong><em>GRADO:</em></strong> </td>
            <td class="no-border" style="width:65%">
                <b><em>{{ $eco_com->degree->shortened ?? '' }}</em></b><br>
            </td>
        </tr>
        <tr>
            <td class="no-border" style="width:35%">
                <strong><em>CATEGORIA:</em></strong>
            </td>
            <td class="no-border" style="width:65%">
                <b><em>{{ $eco_com->category->name ?? '' }}</em> </b><br>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="no-border">
                <strong><em>TOTAL LIQUIDO PAGABLE COMPLEMENTO ECONOMICO Bs:</em></strong><br>
                <em class="size-10" style="border-bottom:2px dotted #000">{{ Util::convertir($eco_com->total)   }}BOLIVIANOS.</em>
                </td>
        </tr>
        @if (isset($eco_com->total_repay))
        <tr>
            <td colspan="2" class="no-border">
                <strong><em>TOTAL PAGADO Bs:</em></strong><br>
                <em class="size-10" style="border-bottom:2px dotted #000">{{ Util::convertir($eco_com->total - $eco_com->total_repay)}}BOLIVIANOS.</em>
                </td>
        </tr>
        <tr>
            <td colspan="2" class="no-border">
                <strong><em>REINTEGRO Bs:</em></strong><br>
                <em class="size-10" style="border-bottom:2px dotted #000">{{ Util::convertir($eco_com->total_repay)}}BOLIVIANOS.</em>
                </td>
        </tr>
        @endif
        </table>
    </div>
    <div class="main-left">
    <table style="width:100%; border:2px solid #464646; border-radius: 20px 20px 20px 20px;" class="text-xsx p-5">
            <tr>
                <th class="no-border text-left w-70" style=""><em>TOTAL COMPLEMENTO ECONOMICO</em></th>
                <td class="no-border text-right w-30" style=""> 
                <strong> <em>Bs. {{ Util::formatMoney($eco_com->getOnlyTotalEcoCom()) ?? '' }}</em></strong>
                </td>
            </tr>
            @foreach ($eco_com->discount_types as $d)
            <tr>
                <td class="no-border text-left w-70 uppercase text-xxs"> <em>- {{ $d->name }}</em></td>
                <td class="w-15 text-right uppercase px-5 py-3"> <em>Bs. {{ Util::formatMoney($d->pivot->amount)}} </em></td>
            </tr> 
            @endforeach
            <tr>
                <th class="no-border text-left w-70" style=""><em>TOTAL LIQUIDO PAGABLE</em></th>
                <td class="no-border text-right w-30" style=""> 
                <strong><em> Bs. {{ Util::formatMoney($eco_com->total) }}</em></strong>
                </td>
            </tr>
            @if (isset($eco_com->total_repay))
            <tr>
                <th class="no-border text-left w-70" style=""><em>TOTAL PAGADO</em></th>
                <td class="no-border text-right w-30" style=""> 
                <strong><em> Bs. {{ Util::formatMoney($eco_com->total- $eco_com->total_repay ) }}</em></strong>
                </td>
            </tr>
            <tr>
                <th class="no-border text-left w-70" style=""><em>REINTEGRO</em></th>
                <td class="no-border text-right w-30" style=""> 
                <strong><em> Bs. {{ Util::formatMoney($eco_com->total_repay) }}</em></strong>
                </td>
            </tr>
            @endif
    </table>
    </div>
</div>