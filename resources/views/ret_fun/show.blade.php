@extends('layouts.app')


@section('title', 'Fondo de Retiro')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{-- Breadcrumbs::render('affiliate') --}}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            Aqui va el detalle del fondo de retiro
            <input type="button" class="btn btn-info" value="Generar PDF" onclick="location.href = '{{asset("ret_fun")}}';">
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection
