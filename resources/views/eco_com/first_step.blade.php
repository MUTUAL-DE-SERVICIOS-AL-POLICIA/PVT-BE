@extends('layouts.app') 
@section('title', 'Afiliados') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9" id="ret-fun-form-header">
        {{-- {{ Breadcrumbs::render('create_eco_com_process', $affiliate) }} --}}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
    <eco-com-search-affiliate :cities="{{ $cities }}">
        </eco-com-search-affiliate>
    </div>
</div>
@endsection