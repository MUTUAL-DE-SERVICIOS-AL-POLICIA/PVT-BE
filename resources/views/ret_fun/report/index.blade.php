@extends('layouts.app') 
@section('title', 'Fondo de Retiro') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('ret_fun_report')!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
        <div class="pull-left">

        </div>
        <div class="pull-right">
        </div>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Seleccion los filtros para generar el reporte</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="ibox-content" style="">
                    <div class="row">
                        <ret-fun-report-form
                            :wf-states="{{ $wf_states }}"
                            :cities="{{ $cities }}"
                        ></ret-fun-report-form>
                    </div>
                </div>
                <div class="ibox-footer">
                    <span class="float-right">footer</span> This is simple footer example
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    
@endsection