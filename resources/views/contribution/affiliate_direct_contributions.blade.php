@extends('layouts.app')
@section('title', 'Contribuciones')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('affiliate_direct_contributions', $affiliate) }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                @can('create',new Muserpol\Models\Contribution\ContributionCommitment)
                    @include('contribution.commitment',['commitment'=>$commitment,'affiliate_id'=>$affiliate->id,'today_date'=>$today_date])
                @else
                @include('contribution.commitment_info',['commitment',$commitment]) @endcan
            </div>
            {{-- <div class="col-md-6">
                @include('contribution.aditional_info',['summary',$summary])
            </div> --}}
        </div>
        <div class="col-md-12 directContribution wrapper wrapper-content animated fadeInRight ">
            <contribution-create :afid="{{ $affiliate->id }}" :last_quotable="{{$last_quotable}}" :commitment="{{ $commitment }}" :is_regional="`{{ $is_regional }}`"></contribution-create>
        </div>
    </div>
</div>
<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
@endsection