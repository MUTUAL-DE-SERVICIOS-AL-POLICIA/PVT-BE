@extends('layouts.app')

@section('title', 'Seleccion de Aportes')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-7">
        {!!Breadcrumbs::render('classify_contributions',$ret_fun)!!}
    </div>
    <div class="col-lg-5" style="padding-top: 15px">
        <a href="{{url('ret_fun/'.$ret_fun->id.'/print/certification')}}" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i> 60 Aportes</a>
        <a href="{{url('ret_fun/'.$ret_fun->id.'/print/cer_availability')}}" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i> Disponibilidad</a>
        <a href="{{url('ret_fun/'.$ret_fun->id.'/print/cer_itemcero')}}" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i> Item 0</a>
    </div>
</div>
{{$date_entry}}
<contribution-select :contributions ="{{json_encode($contributions)}}" :retfunid="{{$ret_fun->id}}" :contype="{{json_encode($con_type)}}" :types="{{json_encode($contribution_types)}}" :start="{{json_encode($date_entry)}}"  :end="{{json_encode($date_derelict)}}" >
</contribution-select>
@endsection