@extends('layouts.app')

@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{--  {{ Breadcrumbs::render('show_affiliate', $affiliate) }}  --}}
    </div>
</div>
icon
<i class='mdi mdi-bell'></i>
icon
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-12">
            <form-wizard-ret-fund></form-wizard-ret-fund>
        </div>
    </div>
</div>

@endsection
