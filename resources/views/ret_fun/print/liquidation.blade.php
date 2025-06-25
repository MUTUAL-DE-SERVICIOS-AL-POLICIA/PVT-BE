@extends('print_global.print')
@section('content')
<div>
    @include('affiliates.print.full_personal_info', ['affiliate'=>$affiliate, 'ret_fun'=>$ret_fun, 'type'=>'quota_aid'])
    @include('ret_fun.print.applicant', ['applicant'=>$applicant])
    @if($ret_fun->hasLegalGuardian())
        @include('ret_fun.print.legal_guardian', ['legal_guardian'=>$ret_fun->ret_fun_beneficiaries()->where('type', 'S')->first()->legal_guardian()->first()])
    @endif
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        DATOS ECONÓMICOS DEL AFILIADO
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr class="text-lg font-bold">
                    <td class="text-left px-10 py-3 uppercase">TOTAL {{$ret_fun->procedure_modality->procedure_type->second_name}}</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($ret_fun->subtotal_ret_fun) }}  Bs.</td>
                </tr>
            </tbody>
        </table>

        @if($ret_fun->discount_types->isNotEmpty() && $ret_fun->discount_types->map->pivot->sum('amount') > 0)
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm">
                        <td colspan="3" class="text-center">RETENCIONES</td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    <tr class="bg-grey-darker font-medium text-white text-sm">
                        <td class="text-center">DESCRIPCIÓN</td>
                        <td class="text-center">DETALLE</td>
                        <td class="text-center">MONTO</td>
                    </tr>
                    @foreach($ret_fun->discount_types as $discount)
                        @if($discount->pivot->amount > 0)
                            <tr class="text-center px-10 py-3 uppercase">
                                <td colspan="3" class="text-center"><b>{{$discount->name}}</b></td>
                            </tr>
                            @if($discount->id == 1 || $discount->id == 11)
                                @if($discount->id == 11)
                                    <tr class="text-sm">
                                        <td class="px-10 text-left">DOCUMENTO</td>
                                        <td class="px-10 text-left">{{$discount->pivot->code}} </td>
                                        <td rowspan="3" class="text-right uppercase px-5 py-3"><b>{{Util::formatMoney($discount->pivot->amount)}} Bs.</b></td>
                                    </tr>
                                    <tr class="text-sm">
                                        <td class="px-10 text-left">DESCRIPCIÓN</td>
                                        <td class="px-10 text-left">{{$discount->pivot->note_code}}</td>
                                    </tr>
                                    <tr class="text-sm">
                                        <td class="px-10 text-left">FECHA</td>
                                        <td class="px-10 text-left">{{$discount->pivot->date}}</td>
                                    </tr>
                                @else
                                    <tr class="text-sm">
                                        <td class="px-10 text-left">DOCUMENTO</td>
                                        <td class="px-10 text-left">{{$discount->pivot->note_code}} </td>
                                        <td rowspan="4" class="text-right uppercase px-5 py-3"><b>{{Util::formatMoney($discount->pivot->amount)}} Bs.</b></td>
                                    </tr>
                                    <tr class="text-sm">
                                        <td class="px-10 text-left">FECHA</td>
                                        <td class="px-10 text-left">{{$discount->pivot->note_code_date}}</td>
                                    </tr>
                                @endif
                            @endif
                            
                            @if($discount->id != 11)
                                <tr class="text-sm">
                                    <td class="px-10 text-left">CERTIFICACIÓN @if($discount->id == 1) DAA @else DESI @endif</td>
                                    <td class="px-10 text-left">{{$discount->pivot->code}}</td>
                                    @if($discount->id != 1) 
                                        <td rowspan="3" class="text-right uppercase px-5 py-3"><b>{{Util::formatMoney($discount->pivot->amount)}} Bs.</b></td>
                                    @endif
                                </tr>
                                <tr class="text-sm">
                                    <td class="px-10 text-left">FECHA</td>
                                    <td class="px-10 text-left">{{$discount->pivot->date}}</td>
                                </tr>
                                @if($discount->id != 1) 
                                    <tr class="text-sm">
                                        <td class="px-10 text-left">NÚMERO DE PRÉSTAMO</td>
                                        <td class="px-10 text-left">{{$discount->pivot->note_code}} </td>
                                    </tr>
                                @endif
                            @endif
                        @endif
                    @endforeach
                    <tr class="bg-grey-darker">
                        <td colspan="3"></td>
                    </tr>
                    <tr class="text-lg font-bold">
                        <td colspan="2" class="text-left px-10 py-3 uppercase">TOTAL {{$ret_fun->procedure_modality->procedure_type->second_name}} - RETENCIONES</td>
                        <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($ret_fun->total_ret_fun) }}  Bs.</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
    @if($beneficiaries->isNotEmpty() || $beneficiaries_minor->isNotEmpty())
        <p class="text-lg">La Comisión de Beneficios Económicos en uso de sus atribuciones, determina el pago del beneficio de 
            {{$ret_fun->procedure_modality->procedure_type->second_name}} en favor de (el) (los) 
            @if($ret_fun->procedure_modality->procedure_type->id == 2 && $ret_fun->procedure_modality->id == 4)
                derechohabiente
            @else
                beneficiario
            @endif
            (s):
        </p>
        <div class="block">
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm uppercase">
                        <td colspan='5' class="px-15 text-center">
                            DETALLE DE PAGO
                        </td>
                    </tr>
                </thead>
                @if($beneficiaries->isNotEmpty())
                    <thead class="bg-grey-darker">
                        <tr class="font-medium text-white text-sm uppercase">
                            <td colspan='8' class="px-15 text-center">
                            </td>
                        </tr>
                    </thead>
                    <tbody class="table-striped">
                        <tr class="text-xs">
                            <td class="w-40 text-center font-bold px-10 py-3">NOMBRE DEL DERECHOHABIENTE / BENEFICIARIO</td>
                            <td class="w-16 text-center font-bold px-10 py-3">C.I.</td>
                            <td class="w-20 text-center font-bold px-10 py-3">% DE ASIGNACIÓN</td>
                            <td class="w-20 text-center font-bold px-10 py-3">MONTO</td>
                            <td class="w-20 text-center font-bold px-10 py-3">PARENTESCO</td>
                        </tr>
                        @foreach ($beneficiaries as $beneficiary)
                            <tr class="text-sm">
                                <td class="text-left uppercase px-5 py-3">{{ $beneficiary->fullName() }}</td>
                                <td class="text-center uppercase px-10 py-3"> {{ $beneficiary->identity_card }} </td>
                                <td class="text-center uppercase px-5 py-3">
                                    <div class="w-70 text-right">{{ $beneficiary->percentage }}</div>
                                </td>
                                <td class="text-center uppercase font-bold px-5 py-3">
                                    {{ Util::formatMoney($beneficiary->amount_ret_fun) }}
                                </td>
                                <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? 'error' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>

            @if($beneficiaries_minor->isNotEmpty())
                <table class="table-info w-100 m-b-10">
                    <thead class="bg-grey-darker">
                        <tr class="font-medium text-white text-sm uppercase">
                            <td colspan='8' class="px-15 text-center">
                            </td>
                        </tr>
                    </thead>
                    <tbody class="table-striped">
                        <tr class="font-medium text-xs">
                            <td class="text-center font-bold">NOMBRE DEL DERECHOHABIENTE</td>
                            <td class="text-center font-bold">C.I.</td>
                            <td class="text-center font-bold">% DE ASIGNACIÓN</td>
                            <td class="text-center font-bold">MONTO</td>
                            <td class="text-center font-bold">PARENTESCO</td>
                            <td class="text-center font-bold">NOMBRE DEL BENEFICIARIO</td>
                            <td class="text-center font-bold">C.I.</td>
                            <td class="text-center font-bold">PARENTESCO</td>
                        </tr>
                        
                        @foreach ($beneficiaries_minor as $beneficiary)
                            @php($advisor = $beneficiary->ret_fun_advisors->first())
                            @php($advisorKinship = $advisor ? $advisor->kinship_beneficiaries($beneficiary->id)->first() : null)   
                            <tr class="text-sm">
                                <td class="text-left uppercase px-5 py-3"> {{ $beneficiary->fullName() }} </td>
                                <td class="text-center uppercase px-10 py-3"> {{ $beneficiary->identity_card }} </td>
                                <td class="text-center uppercase px-5 py-3">
                                    <div class="w-70 text-right">{!! $beneficiary->percentage !!}</div>
                                </td>
                                <td class="text-center uppercase px-5 py-3">
                                    {!! Util::formatMoney($beneficiary->amount_ret_fun) !!}
                                </td>
                                <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? '' }}</td>
                                <td class="text-center uppercase px-5 py-3">{{$advisor ? $advisor->fullName() : ''}}</td>
                                <td class="text-center uppercase px-5 py-3">{{ $advisor ? $advisor->identity_card : ''}}</td>
                                <td class="text-center uppercase px-5 py-3">{{ $advisorKinship ? $advisorKinship->name : ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
    @if(!empty($ret_fun->total_availability))
        <p> Asimismo, la Comisión de Beneficios Económicos en uso de sus atribuciones, determina la 
            devolución de los descuentos realizados al Titular para {{$ret_fun->procedure_modality->procedure_type->second_name}} durante su permanencia
            en el destino de disponibilidad de las letras, en favor de (el) (los)
            @if($ret_fun->procedure_modality->procedure_type->id == 2 && $ret_fun->procedure_modality->id == 4)
                derechohabiente
            @else
                beneficiario
            @endif
            (s):</p>

        @if($beneficiaries->isNotEmpty() || $beneficiaries_minor->isNotEmpty())
            <div class="block">
                <table class="table-info w-100 m-b-10">
                    <thead class="bg-grey-darker">
                        <tr class="font-medium text-white text-sm uppercase">
                            <td colspan='5' class="px-15 text-center">
                                DETALLE DE PAGO
                            </td>
                        </tr>
                    </thead>
                    @if($beneficiaries->isNotEmpty())
                        <thead class="bg-grey-darker">
                            <tr class="font-medium text-white text-sm uppercase">
                                <td colspan='8' class="px-15 text-center">
                                </td>
                            </tr>
                        </thead>
                        <tbody class="table-striped">
                            <tr class="text-xs">
                                <td class="w-40 text-center font-bold px-10 py-3">NOMBRE DEL DERECHOHABIENTE / BENEFICIARIO</td>
                                <td class="w-16 text-center font-bold px-10 py-3">C.I.</td>
                                <td class="w-20 text-center font-bold px-10 py-3">% DE ASIGNACIÓN</td>
                                <td class="w-20 text-center font-bold px-10 py-3">MONTO</td>
                                <td class="w-20 text-center font-bold px-10 py-3">PARENTESCO</td>
                            </tr>
                            @foreach ($beneficiaries as $beneficiary)
                                <tr class="text-sm">
                                    <td class="text-left uppercase px-5 py-3"> {{ $beneficiary->fullName() }} </td>
                                    <td class="text-center uppercase px-10 py-3"> {{ $beneficiary->identity_card }} </td>
                                    <td class="text-center uppercase px-5 py-3">
                                        <div class="w-70 text-right">{{ $beneficiary->percentage }}</div>
                                    </td>
                                    <td class="text-center uppercase font-bold px-5 py-3">
                                        {{Util::formatMoney($ret_fun->total_availability*($beneficiary->percentage/100))}}
                                    </td>
                                    <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                @if($beneficiaries_minor->isNotEmpty())
                    <table class="table-info w-100 m-b-10">
                        <thead class="bg-grey-darker">
                            <tr class="font-medium text-white text-sm uppercase">
                                <td colspan='8' class="px-15 text-center">
                                </td>
                            </tr>
                        </thead>
                        <tbody class="table-striped">
                            <tr class="font-medium text-xs">
                                <td class="text-center font-bold">NOMBRE DEL DERECHOHABIENTE</td>
                                <td class="text-center font-bold">C.I.</td>
                                <td class="text-center font-bold">% DE ASIGNACIÓN</td>
                                <td class="text-center font-bold">MONTO</td>
                                <td class="text-center font-bold">PARENTESCO</td>
                                <td class="text-center font-bold">NOMBRE DEL BENEFICIARIO</td>
                                <td class="text-center font-bold">C.I.</td>
                                <td class="text-center font-bold">PARENTESCO</td>
                            </tr>
                            @foreach ($beneficiaries_minor as $beneficiary)
                                @php($advisor = $beneficiary->ret_fun_advisors->first())
                                @php($advisorKinship = $advisor ? $advisor->kinship_beneficiaries($beneficiary->id)->first() : null)   
                                <tr class="text-sm">
                                    <td class="text-left uppercase px-5 py-3"> {{ $beneficiary->fullName() }} </td>
                                    <td class="text-center uppercase px-10 py-3"> {{ $beneficiary->identity_card }} </td>
                                    <td class="text-center uppercase px-5 py-3">
                                        <div class="w-70 text-right">{{ $beneficiary->percentage }}</div>
                                    </td>
                                    <td class="text-center uppercase px-5 py-3">
                                        {{Util::formatMoney($ret_fun->total_availability*($beneficiary->percentage/100))}}
                                    </td>
                                    <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? '' }}</td>
                                    <td class="text-center uppercase px-5 py-3">{{$advisor ? $advisor->fullName() : ''}}</td>
                                    <td class="text-center uppercase px-5 py-3">{{$advisor ? $advisor->identity_card : ''}}</td>
                                    <td class="text-center uppercase px-5 py-3">{{$advisorKinship ? $advisorKinship->name : ''}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection