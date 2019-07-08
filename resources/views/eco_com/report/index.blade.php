@extends('layouts.app')
@section('title', 'Parametros para la calificacion')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('eco_com_report')!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
        <div class="pull-left">
        </div>
        <div class="pull-right">
            <div class="form-inline">
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <eco-com-select-report :eco-com-procedures="{{ $eco_com_procedures }}"
                        :observation-types="{{$observation_types}}" :wf-states="{{$wf_states }}">
                    </eco-com-select-report>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
@endsection