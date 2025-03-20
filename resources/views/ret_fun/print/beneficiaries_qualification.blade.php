@extends('print_global.print')
@section('content')
<div>
    @include('affiliates.print.full_personal_info', ['affiliate'=>$affiliate, 'ret_fun'=>$retirement_fund, 'type'=>'quota_aid'])
    @include('ret_fun.print.applicant', ['applicant'=>$applicant])
    @if($retirement_fund->hasLegalGuardian())
        @include('ret_fun.print.legal_guardian', ['legal_guardian'=>$retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->first()->legal_guardian()->first()])
    @endif
    @if ($retirement_fund->procedure_modality->id == 1 || $retirement_fund->procedure_modality->id == 4 || $retirement_fund->procedure_modality->id == 63)
        @include('ret_fun.print.beneficiaries_list', ['beneficiaries'=>$beneficiaries])
    @endif
    {{-- @include('ret_fun.print.signature_footer',['user'=>$user]) --}}
    @include('ret_fun.print.signature_footer_2',['qualification_users'=>$qualification_users])
</div>
@endsection