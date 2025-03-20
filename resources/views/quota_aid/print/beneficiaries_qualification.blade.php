@extends('print_global.print')
@section('content')
<div>
    @include('affiliates.print.full_personal_info', ['affiliate'=>$affiliate, 'ret_fun'=>$quota_aid, 'type'=>'quota_aid'])
    @if ( $quota_aid->getDeceased() instanceof Muserpol\Models\Spouse)
        @include('quota_aid.print.deceased_info', ['person' => $quota_aid->getDeceased()])
    @endif
    @include('ret_fun.print.applicant', ['applicant'=>$applicant])
    @if($quota_aid->hasLegalGuardian())
        @include('ret_fun.print.legal_guardian', ['legal_guardian'=>$quota_aid->quota_aid_beneficiaries()->where('type', 'S')->first()->quota_aid_legal_guardians()->first()])
    @endif
    @include('ret_fun.print.beneficiaries_list', ['beneficiaries'=>$beneficiaries])
    @include('ret_fun.print.signature_footer_2',['qualification_users'=>$qualification_users])
</div>
@endsection