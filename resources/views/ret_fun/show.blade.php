@extends('layouts.app')

@section('title', 'Fondo de Retiro')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{--  {!!Breadcrumbs::render('affiliate')!!}  --}}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            Aqui va el detalle del fondo de retiro
            <input type="button" class="btn btn-info" value="Generar PDF" onclick="location.href = '{{asset("ret_fun")}}';">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('ret_fun.applicant_info', ['affiliate'=>$retirement_fund->affiliate])
        </div>
        <div class="md-6">
            @include('ret_fun.info', ['retirement_fund'=>$retirement_fund])
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('ret_fun.beneficiaries_list', ['beneficiaries'=>$beneficiaries])
        </div>
    </div>
</div>
@endsection
