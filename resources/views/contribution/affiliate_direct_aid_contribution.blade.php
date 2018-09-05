@extends('layouts.app') 
@section('title', 'Contribuciones Pasivos') 
 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('affiliate_direct_aid_contributions', $affiliate) }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">    
    <div class="row">
        <div class="col-md-12">           
            <div class="col-md-6">                                
                {{-- @can('update',new Muserpol\Models\Contribution\AidCommitment)                 --}}
                    @include('contribution.aid_commitment',['aid_commitment'=>$commitment,'affiliate_id'=>$affiliate->id,'today_date'=>$today_date])
                {{-- @else --}}
                    {{-- @include('contribution.aid_commitment_info',['commitment',$commitment]) --}}
                {{-- @endcan --}}
            </div>
            

            <div class="col-md-6">                                
                @include('contribution.aid_aditional_info',['summary',$summary])
            </div>
        </div>        
        <div class = "col-md-12">
            <aid-contribution-create
            {{-- :aid-contributions="{{ json_encode($new_contributions) }}" --}}
             :afid="{{ $affiliate->id }}" 
             {{-- :rate="{{ $rate }}" --}}
             ></aid-contribution-create>
        </div>
    </div>
</div>
<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
@endsection