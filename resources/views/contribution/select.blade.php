@extends('layouts.app')

@section('title', 'Seleccion de Aportes')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('classify_contributions',$ret_fun)!!}
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
<contribution-select :contributions ="{{json_encode($contributions)}}" :retfunid="{{$ret_fun->id}}" :contype="{{json_encode($con_type)}}" :types="{{json_encode($contribution_types)}}" :urlcertification="{{ json_encode($url_certification)}}" :ulrzero="{{json_encode($url_certification_itemcero)}}" :urlavailable="{{json_encode($url_certification_itemcero)}}" ></contribution-select>
</div>
@endsection