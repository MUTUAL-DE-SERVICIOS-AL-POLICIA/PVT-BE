@extends('print_global.print')
@section('content')
<div>
    @include('affiliates.print.full_personal_info', ['affiliate'=>$affiliate, 'ret_fun'=>$retirement_fund])
    @include('ret_fun.print.applicant', ['applicant'=>$applicant])
    @if ($retirement_fund->procedure_modality->id == 1 || $retirement_fund->procedure_modality->id == 4)
        @include('ret_fun.print.beneficiaries_list', ['beneficiaries'=>$beneficiaries])
    @endif
    @include('ret_fun.print.signature_footer',['user'=>$user])
</div>
@endsection