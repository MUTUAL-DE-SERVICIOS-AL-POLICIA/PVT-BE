@extends('print_global.print')
@section('content')
<div>
    @include('affiliates.print.full_personal_info', ['affiliate'=>$affiliate, 'ret_fun'=>$quota_aid, 'type'=>'quota_aid'])
    @include('ret_fun.print.applicant', ['applicant'=>$applicant])
    @if ( $quota_aid->getDeceased() instanceof Muserpol\Models\Spouse)
        @include('quota_aid.print.deceased_info', ['person' => $quota_aid->getDeceased()])
    @endif
    @if($quota_aid->hasLegalGuardian())
        @include('ret_fun.print.legal_guardian', ['legal_guardian'=>$quota_aid->quota_aid_beneficiaries()->where('type', 'S')->first()->quota_aid_legal_guardians()->first()])
    @endif
    <div class="block">
    @if ($discount != null && $discount->id == 10)
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='2' class="px-15 text-center">
                    RETENCIÓN JUDICIAL
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr class="text-sm">
                <td class="w-40 text-left px-10 py-3 uppercase">DETALLE</td>
                <td class="text-left uppercase font-bold px-5 py-3"> {{$discount->pivot->note_code}} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">FECHA</td>
                <td class="text-left uppercase font-bold px-5 py-3">{{$discount->pivot->note_code_date}}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">MONTO</td>
                <td class="text-left uppercase font-bold px-5 py-3">{{$discount->pivot->amount}}</td>
            </tr>
        </tbody>
    </table>
    @endif
    </div>
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='3' class="px-15 text-center">
                        DATOS ECONÓMICOS
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                @if ($discount != null)
                <tr class="text-lg">
                    <td class="text-left px-10 py-3 uppercase">SUB TOTAL {{$quota_aid->procedure_modality->procedure_type->second_name}}</td>
                    <td class="text-right uppercase px-5 py-3"> {{ Util::formatMoney($quota_aid->subtotal) }}  Bs.</td>
                </tr>
                @if ($discount->id == 10)
                <tr class="text-md">
                    <td class="text-left px-10 py-3 uppercase"> - {{$discount->shortened}}</td>
                    <td class="text-right uppercase px-5 py-3">- {{ Util::formatMoney($discount->pivot->amount) }}  Bs.</td>
                </tr>
                @endif
                @endif
                <tr class="text-xl font-bold">
                    <td class="text-left px-10 py-3 uppercase">TOTAL {{$quota_aid->procedure_modality->procedure_type->second_name}}</td>
                    <td class="text-right uppercase font-bold px-5 py-3"> {{ Util::formatMoney($quota_aid->total) }}  Bs.</td>
                </tr>
            </tbody>
        </table>
    </div>
    @if($beneficiaries->isNotEmpty() || $beneficiaries_minor->isNotEmpty())
        <p class="text-lg">La Comisión de Beneficios Económicos en uso de sus atribuciones, determina el pago del beneficio de {{$quota_aid->procedure_modality->procedure_type->second_name}} en favor de (el) (los) derechohabiente (s):</p>
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
                            <td class="w-40 text-center font-bold px-10 py-3">NOMBRE DEL DERECHOHABIENTE Y BENEFICIARIO</td>
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
                                    {{ Util::formatMoney($beneficiary->paid_amount) }}
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
                            @php($advisor = $beneficiary->quota_aid_advisors->first())
                            @php($advisorKinship = $advisor ? $advisor->kinship_beneficiaries($beneficiary->id)->first() : null)   
                            <tr class="text-sm">
                                <td class="text-left uppercase px-5 py-3"> {{ $beneficiary->fullName() }} </td>
                                <td class="text-center uppercase px-10 py-3"> {{ $beneficiary->identity_card }} </td>
                                <td class="text-center uppercase px-5 py-3">
                                    <div class="w-70 text-right">{!! $beneficiary->percentage !!}</div>
                                </td>
                                <td class="text-center uppercase px-5 py-3">
                                    {!! Util::formatMoney($beneficiary->paid_amount) !!}
                                </td>
                                <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? '' }}</td>
                                <td class="text-center uppercase px-5 py-3">{{$advisor ? $advisor->last_name : ''}}  {{$advisor ? $advisor->first_name : ''}}</td>
                                <td class="text-center uppercase px-5 py-3">{{$advisor ? $advisor->identity_card : ''}}</td>
                                <td class="text-center uppercase px-5 py-3">{{$advisorKinship ? $advisorKinship->name : ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
</div>
@endsection