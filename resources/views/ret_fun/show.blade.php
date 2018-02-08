@extends('layouts.app')

@section('title', 'Fondo de Retiro')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('affiliate')!!}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row text-center">
        <button class="btn btn-primary dim" type="button"><i class="fa fa-money"></i></button>
        <button class="btn btn-warning dim" type="button"><i class="fa fa-warning"></i></button>
        <button class="btn btn-primary dim" type="button"><i class="fa fa-check"></i></button>
        <button class="btn btn-success  dim" type="button"><i class="fa fa-upload"></i></button>
        <button class="btn btn-info  dim" type="button"><i class="fa fa-paste"></i> </button>
        <button class="btn btn-warning  dim" type="button"><i class="fa fa-warning"></i></button>
        <button class="btn btn-default  dim " type="button"><i class="fa fa-star"></i></button>
        <button class="btn btn-danger  dim " type="button"><i class="fa fa-heart"></i></button>
    </div>
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
        <div class="col-md-6">
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
