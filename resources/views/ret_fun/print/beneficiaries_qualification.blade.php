@extends('print_global.print')
@section('content')
<div>
    @include('affiliates.print.full_personal_info', ['affiliate'=>$affiliate, 'ret_fun'=>$retirement_fund])
    @include('ret_fun.print.applicant', ['applicant'=>$applicant])
    @if ($retirement_fund->procedure_modality->id == 1 || $retirement_fund->procedure_modality->id == 4)
        @include('ret_fun.print.beneficiaries_list', ['beneficiaries'=>$beneficiaries])
    @endif
    <table class="py-100 m-t-50" >
        <tr>
            <td class="no-border text-center text-base w-100 align-bottom">
                <span class="font-bold">
                    ----------------------------------------------------
                </span>
            </td>
        </tr>
        <tr>
            <td class="no-border text-center text-base w-100">
                <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">{!! $user->position !!}</div>
            </td>
        </tr>
    </table>
</div>
@endsection