@extends('layouts.app')

@section('title', 'Seleccion de Aportes')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    {{--  <contribution-create :contributions1="{{ json_encode($contributions) }}" :afid="{{ $affiliate->id }}" ></contribution-create>  --}}
<contribution-select :contributions ="{{json_encode($contributions)}}" :retfunid="{{$ret_fun->id}}" :contype="{{json_encode($con_type)}}" :types="{{json_encode($contribution_types)}}"></contribution-select>
</div>
@endsection