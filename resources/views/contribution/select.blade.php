@extends('layouts.app')

@section('title', 'Seleccion de Aportes')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-7">
        {!!Breadcrumbs::render('classify_contributions',$ret_fun)!!}
    </div>
    <div class="col-lg-5" style="padding-top: 15px">
        <buttons-print-contributions>
        </buttons-print-contributions>
    </div>
</div>
<contribution-select :contributions ="{{json_encode($contributions)}}" :retfunid="{{$ret_fun->id}}" :contype="{{true}}" :types="{{json_encode($contribution_types)}}" :start-date="{{json_encode($date_entry)}}"  :end-date="{{json_encode($date_last_contribution)}}" :contributions-limit="{{ $contributionsLimit }}" :apply-limit='@json($applyLimit)'>
</contribution-select>
@endsection