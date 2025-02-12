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
    @if ($discount != null && $discount->id == 9)
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
                        DATOS ECONOMICOS
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                @if ($discount != null)
                <tr class="text-lg">
                    <td class="text-left px-10 py-3 uppercase">SUB TOTAL {{$quota_aid->procedure_modality->procedure_type->second_name}}</td>
                    <td class="text-right uppercase px-5 py-3"> {{ Util::formatMoney($quota_aid->subtotal) }}  Bs.</td>
                </tr>
                @if ($discount->id == 9)
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
            <tbody class="table-striped">
                <tr>
                    <td class="w-40 text-center font-bold px-10 py-3 uppercase">nombre del beneficiario</td>
                    <td class="w-16 text-center font-bold px-10 py-3 uppercase">c.i.</td>
                    <td class="w-20 text-center font-bold px-10 py-3 uppercase">% de asignacion</td>
                    <td class="w-20 text-center font-bold px-10 py-3 uppercase">monto</td>
                    <td class="w-20 text-center font-bold px-10 py-3 uppercase">parentesco</td>
                </tr>
                @foreach ($beneficiaries as $beneficiary)
                @if ($beneficiary->state)
                <tr class="text-sm">
                    <td class="text-left uppercase px-5 py-3"> {{ $beneficiary->fullName() }} </td>
                    <td class="text-center uppercase px-10 py-3"> {{ $beneficiary->identity_card }} </td>
                    <td class="text-center uppercase px-5 py-3">
                        <div class="w-70 text-right">{!! $beneficiary->percentage !!}</div>
                    </td>
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {!! Util::formatMoney($beneficiary->paid_amount) !!}
                    </td>
                    <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? 'error' }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- @include('ret_fun.print.signature_footer',['user'=>$user]) --}}
</div>
@endsection