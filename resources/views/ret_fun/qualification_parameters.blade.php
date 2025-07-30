@extends('layouts.app')
@section('title', 'Parametros para la calificacion')
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-7">
            {!! Breadcrumbs::render('ret_fun_qualification_parameters') !!}
        </div>

    </div>
    <ret-fun-qualification-parameters :procedures='@json($procedures)' :hierarchies='@json($hierarchies)'></ret-fun-qualification-parameters>
    <br>
@endsection
