@extends('layouts.app')

@section('title', 'Pago de Aportes Directos - Pasivo')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">        
    <aid-contribution-create :contributions1="{{ json_encode($contributions) }}" :affiliate_id="{{ $affiliate->id }}" ></aid-contribution-create>
</div>
@endsection