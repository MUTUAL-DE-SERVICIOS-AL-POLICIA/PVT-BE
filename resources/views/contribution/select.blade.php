@extends('layouts.app')

@section('title', 'Seleccion de Aportes')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-7">
        {!!Breadcrumbs::render('classify_contributions',$ret_fun)!!}
    </div>
    <div class="col-lg-5" style="padding-top: 15px">
        <button onclick="printJS({printable:'{!! url("ret_fun/".$ret_fun->id."/print/certification") !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i> 60 Aportes</button>
        <button onclick="printJS({printable:'{!! url("ret_fun/".$ret_fun->id."/print/cer_availability") !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"  class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i> Disponibilidad</button>
        <button onclick="printJS({printable:'{!! url("ret_fun/".$ret_fun->id."/print/cer_itemcero") !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i> Item 0</button>
    </div>
</div>

<contribution-select :contributions ="{{json_encode($contributions)}}" :retfunid="{{$ret_fun->id}}" :contype="{{json_encode($con_type)}}" :types="{{json_encode($contribution_types)}}" :start="{{json_encode($date_entry)}}"  :end="{{json_encode($date_derelict)}}" >
</contribution-select>
@endsection